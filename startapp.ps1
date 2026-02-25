# ==========================================
# START SCRIPT FOR PROJECTNAME DJANGO APPLICATION
# ==========================================
# CHECK FOR ADMIN PRIVILEGES
if (-not ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Write-Host "ERROR: You must run this script as Administrator to use Port 443!" -ForegroundColor Red
    exit
}

Write-Host "`nStarting PROJECTNAME application..." -ForegroundColor Cyan

$projectPath = "C:\Users\Administrator\PROJECTNAME"
$venvPath = "$projectPath\venv"
$redisExe = "C:\Redis\redis-server.exe"
$port = 443 
$certFile = "$projectPath\localhost+3.pem"
$keyFile = "$projectPath\localhost+3-key.pem"

# --------- 1. Start Redis ---------
Write-Host "`nChecking Redis..."
if (Get-Process -Name "redis-server" -ErrorAction SilentlyContinue) {
    Write-Host "Redis is already running."
}
else {
    if (Test-Path $redisExe) {
        Write-Host "Starting Redis..."
        Start-Process $redisExe -ArgumentList "--port 6379" -WindowStyle Hidden
        Start-Sleep -Seconds 2
        Write-Host "Redis started."
    }
}

# --------- 2. Clear Port 443 ---------
Write-Host "`nChecking if Port $port is clear..."
$portProcess = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue | Select-Object -ExpandProperty OwningProcess -First 1

if ($portProcess) {
    Write-Host "Port $port is busy (PID: $portProcess). Closing existing process..." -ForegroundColor Yellow
    Stop-Process -Id $portProcess -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 1
}

# --------- 3. Activate Virtual Environment ---------
$activate = "$venvPath\Scripts\Activate.ps1"
if (Test-Path $activate) { & $activate } else { Write-Host "Venv not found!"; exit }

# --------- 4. Start Django (Uvicorn with SSL) ---------
Write-Host "`nStarting Django ASGI Server on Production HTTPS Port $port..." -ForegroundColor Green

uvicorn dusrms.asgi:application `
    --host 0.0.0.0 `
    --port $port `
    --log-level info `
    --ssl-certfile="$certFile" `
    --ssl-keyfile="$keyFile" 