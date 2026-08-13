<#
.SYNOPSIS
    Copies the large media files (video, audio, PDFs, captions) into a deployed
    copy of the site.

.DESCRIPTION
    The site's videos, audio narrations, PDFs and caption files total roughly
    1.5 GB. They are excluded by .gitignore (*.mp4, *.mp3, *.pdf, *.vtt) and are
    NOT stored in this repository - GitHub rejects files over 100 MB and one of
    the videos alone is 385 MB. They therefore have to be copied in separately
    on each machine, which is what this script does.

    Without them the site still runs, but: videos stay at 0:00, the Listen
    buttons stay at 0:00, the PDF viewer reports "Unable to load the booklet",
    and document View/Download links 404.

    The source is the original pre-migration project, which is where these files
    still live. Its folder layout differs from the migrated one, so the mapping
    is applied explicitly below:

        <source>\video\*.mp4      ->  public\assets\video\
        <source>\Audio\*.mp3      ->  public\assets\audio\
        <source>\document\*.pdf   ->  public\assets\documents\
        <source>\vtt\*.vtt        ->  public\assets\captions\

    The public\ prefix is not cosmetic: public\ is Apache's DocumentRoot, so
    media copied anywhere above it is simply not reachable by a browser.

    Note: hero-background.mp4 (referenced by the hero section) does not exist in
    the source set. The hero degrades to its gradient background without it.

.PARAMETER Source
    The original project folder containing video/, Audio/, document/, vtt/.

.PARAMETER Target
    Deployment root - the directory that CONTAINS public/, not public/ itself.
    Defaults to D:\xampp2\htdocs\AI-UNIT.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\deploy-media.ps1
#>
[CmdletBinding()]
param(
    [string]$Source = 'C:\Users\tejas\OneDrive\Documents\GitHub\AI-Unit-Website',
    [string]$Target = 'D:\xampp2\htdocs\AI-UNIT',
    # Resolved in the body, not here: $PSScriptRoot is not populated while a
    # param() default is being evaluated under -File, so this would bind an
    # empty path.
    [string]$Manifest,
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'

if (-not $Manifest) { $Manifest = Join-Path $PSScriptRoot 'media-manifest.json' }

if (-not (Test-Path $Source)) { throw "Media source not found: $Source" }
if (-not (Test-Path $Target)) { throw "Target not found: $Target" }
if (-not (Test-Path $Manifest)) { throw "Manifest not found: $Manifest" }

<#
    The source -> target mapping is READ FROM THE MANIFEST rather than written
    out here, so this script and tools\verify-assets.ps1 cannot disagree about
    where media belongs. Previously the mapping lived in both places; when
    public/ became the document root, one of them was a single edit away from
    silently deploying 1.5 GB to a directory nothing serves.

    Only groups with inGit = false are copied - those are the ones excluded
    from git and therefore missing from a fresh clone. The images group is
    version-controlled and arrives with the checkout.
#>
$manifestData = Get-Content -LiteralPath $Manifest -Raw -Encoding UTF8 | ConvertFrom-Json

$map = @()
foreach ($group in $manifestData.mediaGroups) {
    if ($group.inGit -or -not $group.sourceDir) { continue }
    $map += @{
        From   = $group.sourceDir
        To     = (Join-Path $manifestData.documentRoot ($group.targetDir -replace '/', '\'))
        Filter = $group.filter
    }
}

if ($map.Count -eq 0) { throw "No deployable media groups found in $Manifest" }

Write-Host "Media source: $Source"
Write-Host "Target:       $Target"
if ($DryRun) { Write-Host "(dry run)" -ForegroundColor Yellow }
Write-Host ""

$copied = 0
$skipped = 0

foreach ($entry in $map) {
    $srcDir = Join-Path $Source $entry.From
    $dstDir = Join-Path $Target $entry.To

    if (-not (Test-Path $srcDir)) {
        Write-Host "SKIP (missing source): $($entry.From)" -ForegroundColor Yellow
        continue
    }
    if (-not $DryRun -and -not (Test-Path $dstDir)) {
        New-Item -ItemType Directory -Path $dstDir -Force | Out-Null
    }

    Write-Host "$($entry.From) -> $($entry.To)"
    Get-ChildItem -Path $srcDir -Filter $entry.Filter -File | ForEach-Object {
        $destFile = Join-Path $dstDir $_.Name

        # Media files are large and immutable; match on size to avoid rehashing
        # hundreds of megabytes on every run.
        if ((Test-Path $destFile) -and ((Get-Item $destFile).Length -eq $_.Length)) {
            $script:skipped++
            return
        }

        $mb = [math]::Round($_.Length / 1MB, 1)
        Write-Host ("  {0}  ({1} MB)" -f $_.Name, $mb)
        if (-not $DryRun) { Copy-Item $_.FullName $destFile -Force }
        $script:copied++
    }
}

Write-Host ""
Write-Host "Copied: $copied   Already present: $skipped" -ForegroundColor Green
Write-Host ""
Write-Host "Confirm the deployment has everything the site needs:" -ForegroundColor Cyan
Write-Host "  powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -ProjectRoot `"$Target`"" -ForegroundColor Cyan
