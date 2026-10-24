param([switch]$Quiet)

$ErrorActionPreference = 'Stop'
# Use TLS 1.2 for legacy PowerShell / Windows installations.
[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.SecurityProtocolType]::Tls12
$project = Split-Path -Parent $PSScriptRoot
$manifestFile = Join-Path $project 'DANH_SACH_ANH_PEXELS.json'
$dest = Join-Path $project 'public\images\menu\real'
New-Item -ItemType Directory -Force -Path $dest | Out-Null
$photos = Get-Content -LiteralPath $manifestFile -Encoding UTF8 -Raw | ConvertFrom-Json

function Test-Jpeg([string]$file) {
    if (!(Test-Path -LiteralPath $file)) { return $false }
    $fi = Get-Item -LiteralPath $file
    if ($fi.Length -lt 10000) { return $false }
    $s = [System.IO.File]::OpenRead($file)
    try { return ($s.ReadByte() -eq 255 -and $s.ReadByte() -eq 216 -and $s.ReadByte() -eq 255) }
    finally { $s.Close() }
}

function Get-PhotoUrlFromPage([string]$pageUrl) {
    try {
        $page = Invoke-WebRequest -Uri $pageUrl -TimeoutSec 20 -UseBasicParsing -Headers @{'User-Agent'='Mozilla/5.0'}
        $tags = [regex]::Matches($page.Content, '<meta\b[^>]*>', 'IgnoreCase')
        foreach ($item in $tags) {
            $tag = $item.Value
            if ($tag -match 'property=["'']og:image["'']' -and $tag -match 'content=["'']([^"'']+)["'']') {
                $result = [System.Net.WebUtility]::HtmlDecode($Matches[1])
                if ($result.StartsWith('https://images.pexels.com/')) { return $result }
            }
        }
    } catch { }
    return $null
}

$ok = 0
$skipped = 0
$failed = @()
$total = @($photos).Count
Write-Host "Real photo downloader: $total selected free-use Pexels photos"
Write-Host "Target: $dest"
foreach ($entry in $photos) {
    $slug = [string]$entry.slug
    if ($slug -notmatch '^[a-z0-9-]+$') { throw "Invalid slug in manifest: $slug" }
    $id = [int]$entry.photo_id
    $final = Join-Path $dest ($slug+'.jpg')
    if (Test-Jpeg $final) { $skipped++; Write-Host "SKIP $slug"; continue }
    $temporary = $final+'.download'
    if (Test-Path $temporary) { Remove-Item -LiteralPath $temporary -Force }
    $candidates = @(
        "https://images.pexels.com/photos/$id/pexels-photo-$id.jpeg?cs=tinysrgb&w=1000",
        "https://images.pexels.com/photos/$id/pexels-photo-$id.jpg?cs=tinysrgb&w=1000"
    )
    $success = $false
    $usedPageFallback = $false
    foreach ($candidate in $candidates) {
        try {
            Invoke-WebRequest -Uri $candidate -OutFile $temporary -UseBasicParsing -TimeoutSec 25 -Headers @{'User-Agent'='Mozilla/5.0'}
            if (Test-Jpeg $temporary) {
                Move-Item -LiteralPath $temporary -Destination $final -Force
                $success = $true
                break
            }
        } catch { }
        if (Test-Path $temporary) { Remove-Item -LiteralPath $temporary -Force }
    }
    if (-not $success) {
        $url = Get-PhotoUrlFromPage ([string]$entry.source_page)
        if ($url) {
            try {
                Invoke-WebRequest -Uri $url -OutFile $temporary -UseBasicParsing -TimeoutSec 30 -Headers @{'User-Agent'='Mozilla/5.0'}
                if (Test-Jpeg $temporary) {
                    Move-Item -LiteralPath $temporary -Destination $final -Force
                    $success = $true
                }
            } catch { }
            if (Test-Path $temporary) { Remove-Item -LiteralPath $temporary -Force }
        }
    }
    if ($success) {
        $ok++
        Write-Host "OK   $slug"
    } else {
        $failed += $slug
        Write-Host "FAIL $slug - still using bundled SVG illustration" -ForegroundColor Yellow
    }
    Start-Sleep -Milliseconds 100
}
Write-Host ""
Write-Host "DONE. Photos downloaded: $ok; already present: $skipped; not downloaded: $($failed.Count)" -ForegroundColor Cyan
Write-Host "Photos display automatically in Universal Tea after refreshing the web browser."
if ($failed.Count -gt 0) {
    Write-Host 'Unsuccessful downloads will show the original SVG automatically.' -ForegroundColor Yellow
    Write-Host 'You can run TAI_ANH_THUC_TE.cmd again to retry.'
    exit 2
}
exit 0
