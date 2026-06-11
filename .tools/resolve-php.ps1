function Resolve-ProjectPhp {
    param(
        [string[]] $AdditionalCandidates = @()
    )

    $candidates = New-Object System.Collections.Generic.List[string]

    if ($env:PHP_PATH) {
        $candidates.Add($env:PHP_PATH)
    }

    try {
        $phpCommand = Get-Command php -ErrorAction Stop
        if ($phpCommand.Path) {
            $candidates.Add($phpCommand.Path)
        }
    } catch {
    }

    foreach ($candidate in $AdditionalCandidates) {
        if ($candidate) {
            $candidates.Add($candidate)
        }
    }

    $laragonRoots = @(
        'C:\laragon\bin\php',
        'D:\laragon\bin\php'
    )

    foreach ($root in $laragonRoots) {
        if (Test-Path $root) {
            Get-ChildItem -Path $root -Filter php.exe -Recurse -ErrorAction SilentlyContinue |
                Sort-Object FullName -Descending |
                ForEach-Object { $candidates.Add($_.FullName) }
        }
    }

    $resolved = $candidates |
        Where-Object { $_ -and (Test-Path $_) } |
        Select-Object -Unique -First 1

    if (-not $resolved) {
        throw "No se encontró php.exe. Definí PHP_PATH o instalá/agregá Laragon al PATH."
    }

    $phpDir = Split-Path -Parent $resolved
    if (-not (($env:PATH -split ';') -contains $phpDir)) {
        $env:PATH = "$phpDir;$env:PATH"
    }

    return $resolved
}

