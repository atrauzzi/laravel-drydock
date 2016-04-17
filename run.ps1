# docker and docker-compose don't support interactive mode on Windows yet.
# This sucks for 3 reasons:
# 
#  - You can't interact with commands
#  - You can't know when a container is done without checking
#  - You can't automatically delete containers after they're finished running
#
# https://github.com/docker/compose/issues/3194
#
# Once the current insider version of Windows with VT100 support is released and Docker has caught up to that fact, 
# this script should end up looking more like it's bash equivalent.

param(
    [Parameter(Mandatory=$true)] [string] $entrypoint,
    [Parameter(ValueFromRemainingArguments=$true)] $remainingArgs
)

Write-Host "`nGreetings Windows user!  `n`nWe're still waiting on a few things to be resolved, in the meantime, commands have to be run non-interactively."

# For some reason...
if($entrypoint -eq "artisan") {
    $entrypoint = "php /var/www/artisan"
}

$containerName = docker-compose run `
    --rm `
    -d `
    --entrypoint=$entrypoint `
    webapp $remainingArgs

$containerName = $containerName.Replace('\n', '')

Write-Host -NoNewLine "Running in: $containerName"

# We have to wait until the container is done doing it's thing.
# This is reason 1 of 3 that not having interactive mode on Windows sucks.
$running = $true
do {

    $check = docker ps --filter=name=$containerName -a
    
    $test = Select-String -InputObject $check -Pattern "Exited"

    if($test.Length -gt 0) {
        $running = $false
    }

    Write-Host -NoNewLine "."
    sleep 1

}while($running -eq $true)

Write-Host "`n"

$logs = docker logs $containerName
# Because I'm such a bro, fix newlines for the Windows types... For now.
echo $logs.Replace('\n', '\n\r');

$remove = docker rm $($containerName)

Write-Host ""