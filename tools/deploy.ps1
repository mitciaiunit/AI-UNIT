<#
.SYNOPSIS
    Deploys this repository's code to the local Apache document root.

.DESCRIPTION
    Apache serves the site from its own directory (e.g. D:\xampp2\htdocs\AI-UNIT),
    which is a SEPARATE copy from this git working tree. Editing a file here has
    no effect on the running site until it is copied across - a mismatch that has
    repeatedly looked like "my change didn't work" when the change was fine and
    simply wasn't deployed.

    This script makes that copy explicit and repeatable. It copies tracked code
    only, and deliberately does NOT touch:

      .env                    per-machine secrets (DB password, SMTP credentials)
      vendor/                 Composer packages - run `composer install` in the target
      storage/logs/           runtime logs
      public/uploads/         admin-uploaded gallery images, which exist only in
                              the target and have no copy in this repository
      public/assets/video,audio,documents,captions
                              large media files (~1.5 GB) that are gitignored and
                              are NOT in this repo - see tools/deploy-media.ps1

    LAYOUT: the target keeps the repository's shape - private code above,
    public/ below - and Apache's DocumentRoot points at <target>\public, not at
    <target>. Deploying this tree to a DocumentRoot of <target> would publish
    .env and the whole application source; see the README's Apache section.

    Run this after every change you want to see in the browser.

.PARAMETER Target
    Deployment root - the directory that CONTAINS public/, not public/ itself.
    Defaults to D:\xampp2\htdocs\AI-UNIT.

.PARAMETER DryRun
    Show what would be copied without writing anything.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\deploy.ps1
    powershell -ExecutionPolicy Bypass -File tools\deploy.ps1 -DryRun
#>
[CmdletBinding()]
param(
    [string]$Target = 'D:\xampp2\htdocs\AI-UNIT',
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'
$Source = Split-Path -Parent $PSScriptRoot

if (-not (Test-Path $Target)) {
    throw "Target does not exist: $Target"
}

Write-Host "Source: $Source"
Write-Host "Target: $Target"
if ($DryRun) { Write-Host "(dry run - nothing will be written)" -ForegroundColor Yellow }
Write-Host ""

# Private application directories. These sit ABOVE the document root, which is
# <target>\public - see the README's Apache section. They are copied so the
# application can run, never so a browser can read them.
$codeDirs = @('app', 'config', 'database', 'includes', 'pages', 'routes', 'api')

$codeFiles = @(
    'bootstrap.php', 'router.php', 'composer.json', 'composer.lock',
    '.env.example', '.gitignore', 'README.md',
    # Backstop deny rules for a server whose DocumentRoot is set one directory
    # too high. Not read at all under a correct DocumentRoot.
    '.htaccess',
    # vendor/ is rebuilt in the target by `composer install`, which knows
    # nothing about this file, so it has to be placed explicitly or the target
    # would be the one deployment missing it.
    'vendor\.htaccess'
)

# The document root itself. Copied as three explicit pieces rather than
# wholesale, because public/ now also contains the two trees a code deploy must
# never touch:
#
#   public\assets\video|audio|documents|captions   ~1.5 GB of deploy-only media
#                                                  (tools\deploy-media.ps1)
#   public\uploads                                 admin-uploaded gallery images
#                                                  that exist only in the target
#
# Copying public\ in one sweep would drag the first across on every deploy and,
# worse, is the kind of rule that later grows a "clean the target first" step
# and destroys the second.
$publicDirs = @('assets\css', 'assets\js', 'assets\images')
$publicFiles = @(
    'index.php',
    '.htaccess',
    # Declares the .vtt MIME type Apache lacks by default; without it captions
    # are served with no Content-Type and browsers ignore them.
    'assets\.htaccess'
)

$copied = 0

function Copy-Tree($relPath) {
    $src = Join-Path $Source $relPath
    $dst = Join-Path $Target $relPath
    if (-not (Test-Path $src)) { return }

    # -Force so dotfiles are included. Every .htaccess in this project is a
    # security control, and Get-ChildItem skips hidden files without it - which
    # would deploy the application with its deny rules silently missing.
    Get-ChildItem -Path $src -Recurse -File -Force | ForEach-Object {
        $rel = $_.FullName.Substring($src.Length).TrimStart('\')
        $destFile = Join-Path $dst $rel
        $destDir = Split-Path -Parent $destFile

        $differs = $true
        if (Test-Path $destFile) {
            $differs = (Get-FileHash $_.FullName).Hash -ne (Get-FileHash $destFile).Hash
        }
        if (-not $differs) { return }

        Write-Host "  $relPath\$rel"
        if (-not $DryRun) {
            if (-not (Test-Path $destDir)) { New-Item -ItemType Directory -Path $destDir -Force | Out-Null }
            Copy-Item $_.FullName $destFile -Force
        }
        $script:copied++
    }
}

function Copy-File($relPath) {
    $src = Join-Path $Source $relPath
    $dst = Join-Path $Target $relPath
    if (-not (Test-Path $src)) { return }

    $differs = $true
    if (Test-Path $dst) {
        $differs = (Get-FileHash $src).Hash -ne (Get-FileHash $dst).Hash
    }
    if (-not $differs) { return }

    Write-Host "  $relPath"
    if (-not $DryRun) {
        $destDir = Split-Path -Parent $dst
        if (-not (Test-Path $destDir)) { New-Item -ItemType Directory -Path $destDir -Force | Out-Null }
        Copy-Item $src $dst -Force
    }
    $script:copied++
}

Write-Host "Private application directories (above the document root):"
foreach ($d in $codeDirs) { Copy-Tree $d }

Write-Host "Document root - public\assets (css/js/images only; media left untouched):"
foreach ($d in $publicDirs) { Copy-Tree "public\$d" }

Write-Host "Document root - public files:"
foreach ($f in $publicFiles) { Copy-File "public\$f" }

Write-Host "Root files:"
foreach ($f in $codeFiles) { Copy-File $f }

Write-Host ""
if ($copied -eq 0) {
    Write-Host "Already up to date - nothing to copy." -ForegroundColor Green
} elseif ($DryRun) {
    Write-Host "$copied file(s) would be copied." -ForegroundColor Yellow
} else {
    Write-Host "Deployed $copied file(s)." -ForegroundColor Green
    Write-Host "Hard-refresh the browser (Ctrl+F5) if CSS/JS looks stale." -ForegroundColor Green
}
