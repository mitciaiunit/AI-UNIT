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

        <source>\video\*.mp4      ->  assets\video\
        <source>\Audio\*.mp3      ->  assets\audio\
        <source>\document\*.pdf   ->  assets\documents\
        <source>\vtt\*.vtt        ->  assets\captions\

    Note: hero-background.mp4 (referenced by the hero section) does not exist in
    the source set. The hero degrades to its gradient background without it.

.PARAMETER Source
    The original project folder containing video/, Audio/, document/, vtt/.

.PARAMETER Target
    Deployed site root. Defaults to D:\xampp2\htdocs\AI-UNIT.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\deploy-media.ps1
#>
[CmdletBinding()]
param(
    [string]$Source = 'C:\Users\tejas\OneDrive\Documents\GitHub\AI-Unit-Website',
    [string]$Target = 'D:\xampp2\htdocs\AI-UNIT',
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'

if (-not (Test-Path $Source)) { throw "Media source not found: $Source" }
if (-not (Test-Path $Target)) { throw "Target not found: $Target" }

# sourceSubfolder -> targetAssetSubfolder, plus the file pattern to copy.
$map = @(
    @{ From = 'video';    To = 'assets\video';     Filter = '*.mp4' },
    @{ From = 'Audio';    To = 'assets\audio';     Filter = '*.mp3' },
    @{ From = 'document'; To = 'assets\documents'; Filter = '*.pdf' },
    @{ From = 'vtt';      To = 'assets\captions';  Filter = '*.vtt' }
)

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
