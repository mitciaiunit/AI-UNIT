<#
.SYNOPSIS
    Verifies that every media asset the website requires is actually present.

.DESCRIPTION
    The site's videos, audio narrations, PDFs and caption files are excluded
    from git (.gitignore drops *.mp4, *.mp3, *.pdf, *.vtt - one video alone is
    385 MB) and are copied in separately by tools\deploy-media.ps1. A fresh
    clone therefore looks complete while 23 of its media files are absent, and
    the failure is invisible until someone clicks a Listen, View or Download
    control and gets a 404.

    This script makes that failure loud and early. It reads
    tools\media-manifest.json - the list of assets the application actually
    references, with the size and SHA-256 of each - and reports anything
    missing, empty, truncated or altered.

    Exit code 0 means every required asset is present and valid. Any other
    result exits 1, so the script can gate a deployment or a CI step.

    Assets marked "required": false in the manifest report as warnings rather
    than failures. That is how a known, accepted gap is recorded without
    turning every run red - see assets/images/yr.jpg, which is waiting on work
    order WO-05.

.PARAMETER ProjectRoot
    The directory that CONTAINS public/, i.e. the repository or deployment
    root. Defaults to the parent of the folder holding this script, so running
    it from a clone needs no arguments at all.

.PARAMETER Manifest
    Path to the manifest. Defaults to media-manifest.json beside this script.

.PARAMETER BaseUrl
    Optional. When given, each asset is ALSO fetched over HTTP from this base
    and checked for a 200 and the expected Content-Type. This is the only way
    to catch server-side problems that a filesystem check cannot see - a
    missing MIME mapping, a deny rule that is too broad, a document root
    pointing somewhere unexpected. Captions are the standing example: browsers
    ignore a .vtt track that is not served as text/vtt, so the file passes on
    disk and the subtitles still never appear.

.PARAMETER Checksum
    Also compare the SHA-256 of every file against the manifest. Slower (it
    reads ~1.5 GB), so it is opt-in; size is always compared, which already
    catches the realistic failure of a copy that stopped part-way.

.PARAMETER UpdateManifest
    Rewrites the size and SHA-256 of every asset from the files currently on
    disk, then exits. Use this after legitimately replacing a media file -
    never to silence a failure you have not explained.

.PARAMETER Quiet
    Suppress the per-asset OK lines; show only warnings, failures and the
    summary.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -BaseUrl http://localhost:8081

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File tools\verify-assets.ps1 -ProjectRoot D:\xampp2\htdocs\AI-UNIT -Checksum
#>
[CmdletBinding()]
param(
    [string]$ProjectRoot,
    [string]$Manifest,
    [string]$BaseUrl,
    [switch]$Checksum,
    [switch]$UpdateManifest,
    [switch]$Quiet
)

$ErrorActionPreference = 'Stop'

# Defaults are derived from where this script sits, never from a machine
# specific path, so a clone works unmodified on any developer's disk.
if (-not $ProjectRoot) { $ProjectRoot = Split-Path -Parent $PSScriptRoot }
if (-not $Manifest)    { $Manifest    = Join-Path $PSScriptRoot 'media-manifest.json' }

if (-not (Test-Path -LiteralPath $ProjectRoot)) {
    Write-Host "Project root not found: $ProjectRoot" -ForegroundColor Red
    exit 1
}
if (-not (Test-Path -LiteralPath $Manifest)) {
    Write-Host "Manifest not found: $Manifest" -ForegroundColor Red
    exit 1
}

$ProjectRoot = (Resolve-Path -LiteralPath $ProjectRoot).Path
$Manifest    = (Resolve-Path -LiteralPath $Manifest).Path

$data = Get-Content -LiteralPath $Manifest -Raw -Encoding UTF8 | ConvertFrom-Json
$docRoot = Join-Path $ProjectRoot $data.documentRoot

if (-not (Test-Path -LiteralPath $docRoot)) {
    Write-Host "Document root not found: $docRoot" -ForegroundColor Red
    Write-Host "Expected '$($data.documentRoot)' inside '$ProjectRoot'." -ForegroundColor Red
    exit 1
}
$docRoot = (Resolve-Path -LiteralPath $docRoot).Path

# Content-Type each extension must be served with. Only consulted in -BaseUrl
# mode; .vtt is the one Apache has no default mapping for, which is why
# public/assets/.htaccess declares it explicitly.
$expectedType = @{
    '.mp4'  = 'video/mp4'
    '.mp3'  = 'audio/mpeg'
    '.vtt'  = 'text/vtt'
    '.pdf'  = 'application/pdf'
    '.png'  = 'image/png'
    '.jpg'  = 'image/jpeg'
    '.jpeg' = 'image/jpeg'
    '.gif'  = 'image/gif'
    '.webp' = 'image/webp'
}

# ---------------------------------------------------------------------------
# -UpdateManifest: refresh sizes and hashes from disk, then stop.
# ---------------------------------------------------------------------------
if ($UpdateManifest) {
    $updated = 0
    $absent  = 0

    foreach ($asset in $data.assets) {
        $full = Join-Path $docRoot ($asset.path -replace '/', '\')
        if (Test-Path -LiteralPath $full) {
            $asset.bytes  = (Get-Item -LiteralPath $full).Length
            $asset.sha256 = (Get-FileHash -LiteralPath $full -Algorithm SHA256).Hash.ToLower()
            $updated++
        } else {
            $asset.bytes  = $null
            $asset.sha256 = $null
            $absent++
            Write-Host "  absent, cleared: $($asset.path)" -ForegroundColor Yellow
        }
    }

    $json = $data | ConvertTo-Json -Depth 10
    Set-Content -LiteralPath $Manifest -Value $json -Encoding UTF8

    Write-Host ""
    Write-Host "Manifest updated: $updated file(s) hashed, $absent absent." -ForegroundColor Green
    Write-Host "Review the diff before keeping it - a changed hash means the file changed." -ForegroundColor Yellow
    exit 0
}

# ---------------------------------------------------------------------------
# Verification
# ---------------------------------------------------------------------------
$checks = @('exists', 'size')
if ($Checksum) { $checks += 'sha256' }
if ($BaseUrl)  { $checks += 'http' }

Write-Host ""
Write-Host "AI Unit - media asset verification" -ForegroundColor Cyan
Write-Host "  Project root  : $ProjectRoot"
Write-Host "  Document root : $docRoot"
Write-Host "  Manifest      : $Manifest"
Write-Host "  Checks        : $($checks -join ', ')"
if ($BaseUrl) { Write-Host "  Base URL      : $BaseUrl" }
Write-Host ""

$failures = New-Object System.Collections.ArrayList
$warnings = New-Object System.Collections.ArrayList
$okCount  = 0
$lastGroup = ''

foreach ($asset in $data.assets) {
    if ($asset.group -ne $lastGroup) {
        if (-not $Quiet) {
            Write-Host ""
            Write-Host ("[{0}]" -f $asset.group) -ForegroundColor Cyan
        }
        $lastGroup = $asset.group
    }

    $full = Join-Path $docRoot ($asset.path -replace '/', '\')
    $problems = New-Object System.Collections.ArrayList

    if (-not (Test-Path -LiteralPath $full)) {
        [void]$problems.Add('file not found on disk')
    } else {
        $item = Get-Item -LiteralPath $full

        if ($item.Length -eq 0) {
            [void]$problems.Add('file is empty (0 bytes)')
        } elseif ($null -ne $asset.bytes -and $item.Length -ne $asset.bytes) {
            [void]$problems.Add("size is $($item.Length) bytes, manifest expects $($asset.bytes) - the file is truncated or has been replaced")
        }

        if ($Checksum -and $problems.Count -eq 0 -and $asset.sha256) {
            $actual = (Get-FileHash -LiteralPath $full -Algorithm SHA256).Hash.ToLower()
            if ($actual -ne $asset.sha256.ToLower()) {
                [void]$problems.Add("SHA-256 mismatch - contents differ from the manifest")
            }
        }

        if ($BaseUrl -and $problems.Count -eq 0) {
            $url = ($BaseUrl.TrimEnd('/')) + '/' + $asset.path
            try {
                # HEAD, so the ~1.5 GB of media is never actually transferred.
                $resp = Invoke-WebRequest -Uri $url -Method Head -UseBasicParsing -TimeoutSec 20
                $status = [int]$resp.StatusCode
                if ($status -ne 200) {
                    [void]$problems.Add("HTTP $status at $url")
                } else {
                    $ext = [System.IO.Path]::GetExtension($asset.path).ToLower()
                    if ($expectedType.ContainsKey($ext)) {
                        $ctype = ($resp.Headers['Content-Type'] -split ';')[0].Trim()
                        if ($ctype -ne $expectedType[$ext]) {
                            [void]$problems.Add("served as '$ctype', expected '$($expectedType[$ext])'")
                        }
                    }
                }
            } catch {
                $code = ''
                if ($_.Exception.Response) { $code = [int]$_.Exception.Response.StatusCode }
                if ($code) {
                    [void]$problems.Add("HTTP $code at $url")
                } else {
                    [void]$problems.Add("request failed at $url - $($_.Exception.Message)")
                }
            }
        }
    }

    if ($problems.Count -eq 0) {
        $okCount++
        if (-not $Quiet) { Write-Host ("  OK    {0}" -f $asset.path) -ForegroundColor DarkGray }
        continue
    }

    $entry = [PSCustomObject]@{
        Path     = $asset.path
        UsedBy   = $asset.usedBy
        Note     = $asset.note
        Problems = $problems
    }

    if ($asset.required) {
        [void]$failures.Add($entry)
        Write-Host ("  FAIL  {0}" -f $asset.path) -ForegroundColor Red
    } else {
        [void]$warnings.Add($entry)
        Write-Host ("  WARN  {0}" -f $asset.path) -ForegroundColor Yellow
    }
}

$requiredTotal = ($data.assets | Where-Object { $_.required }).Count

Write-Host ""
Write-Host ("-" * 72)

if ($warnings.Count -gt 0) {
    Write-Host ""
    Write-Host "WARNINGS - known gaps, not treated as failures" -ForegroundColor Yellow
    foreach ($w in $warnings) {
        Write-Host ("  {0}" -f $w.Path) -ForegroundColor Yellow
        foreach ($p in $w.Problems) { Write-Host "      $p" }
        Write-Host "      used by: $($w.UsedBy)"
        if ($w.Note) { Write-Host "      note:    $($w.Note)" }
    }
}

if ($failures.Count -gt 0) {
    Write-Host ""
    Write-Host "FAILURES" -ForegroundColor Red
    foreach ($f in $failures) {
        Write-Host ("  {0}" -f $f.Path) -ForegroundColor Red
        foreach ($p in $f.Problems) { Write-Host "      $p" -ForegroundColor Red }
        Write-Host "      used by: $($f.UsedBy)"
    }
    Write-Host ""
    Write-Host "If media is simply not deployed on this machine, copy it in with:" -ForegroundColor Yellow
    Write-Host "  powershell -ExecutionPolicy Bypass -File tools\deploy-media.ps1 -Source <media folder>" -ForegroundColor Yellow
    Write-Host ""
    Write-Host ("RESULT: FAIL - {0} of {1} required assets have problems ({2} warnings)" -f $failures.Count, $requiredTotal, $warnings.Count) -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host ("RESULT: PASS - all {0} required assets present and valid ({1} warnings)" -f $requiredTotal, $warnings.Count) -ForegroundColor Green
exit 0
