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

      .env            per-machine secrets (DB password, SMTP credentials)
      vendor/         Composer packages - run `composer install` in the target
      storage/logs/   runtime logs
      uploads/        user-uploaded content
      assets/video,audio,documents,captions
                      large media files (~1.5 GB) that are gitignored and are
                      NOT in this repo - see tools/deploy-media.ps1

    Run this after every change you want to see in the browser.

.PARAMETER Target
    Document-root copy to deploy into. Defaults to D:\xampp2\htdocs\AI-UNIT.

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

# Directories copied wholesale. assets/ is handled separately below so that the
# large gitignored media folders in the target are preserved.
$codeDirs = @('app', 'config', 'database', 'includes', 'pages', 'public', 'routes', 'api')
$codeFiles = @(
    'bootstrap.php', 'composer.json', 'composer.lock', '.env.example', '.gitignore', 'README.md',
    # Declares the .vtt MIME type Apache lacks by default; without it captions
    # are served with no Content-Type and browsers ignore them.
    'assets\.htaccess'
)

# Only these asset subfolders are version-controlled; video/audio/documents/
# captions hold deploy-only media and must never be wiped by a code deploy.
$assetDirs = @('css', 'js', 'images')

$copied = 0

function Copy-Tree($relPath) {
    $src = Join-Path $Source $relPath
    $dst = Join-Path $Target $relPath
    if (-not (Test-Path $src)) { return }

    Get-ChildItem -Path $src -Recurse -File | ForEach-Object {
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

Write-Host "Code directories:"
foreach ($d in $codeDirs) { Copy-Tree $d }

Write-Host "Assets (css/js/images only - media folders left untouched):"
foreach ($d in $assetDirs) { Copy-Tree "assets\$d" }

Write-Host "Root files:"
foreach ($f in $codeFiles) {
    $src = Join-Path $Source $f
    $dst = Join-Path $Target $f
    if (-not (Test-Path $src)) { continue }

    $differs = $true
    if (Test-Path $dst) {
        $differs = (Get-FileHash $src).Hash -ne (Get-FileHash $dst).Hash
    }
    if (-not $differs) { continue }

    Write-Host "  $f"
    if (-not $DryRun) { Copy-Item $src $dst -Force }
    $copied++
}

Write-Host ""
if ($copied -eq 0) {
    Write-Host "Already up to date - nothing to copy." -ForegroundColor Green
} elseif ($DryRun) {
    Write-Host "$copied file(s) would be copied." -ForegroundColor Yellow
} else {
    Write-Host "Deployed $copied file(s)." -ForegroundColor Green
    Write-Host "Hard-refresh the browser (Ctrl+F5) if CSS/JS looks stale." -ForegroundColor Green
}
