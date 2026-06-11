$ErrorActionPreference = 'Stop'

. "$PSScriptRoot\.tools\resolve-php.ps1"

$php = Resolve-ProjectPhp
$phpDir = Split-Path -Parent $php

Write-Host "Usando PHP: $php" -ForegroundColor Cyan
Write-Host "Iniciando entorno dev de VehiPark..." -ForegroundColor Green

$env:PATH = "$phpDir;$env:PATH"

npx concurrently -c "#93c5fd,#c4b5fd,#fdba74" "php artisan serve" "php artisan queue:listen --tries=1" "npm run dev" --names=server,queue,vite
exit $LASTEXITCODE
