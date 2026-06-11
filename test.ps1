$ErrorActionPreference = 'Stop'

. "$PSScriptRoot\.tools\resolve-php.ps1"

$php = Resolve-ProjectPhp
Write-Host "Usando PHP: $php" -ForegroundColor Cyan

& $php artisan test @args
exit $LASTEXITCODE

