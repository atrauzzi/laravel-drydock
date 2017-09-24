param(
    [Parameter(Mandatory=$false)] [string] $entrypoint,
    [Parameter(ValueFromRemainingArguments=$false)] $remainingArgs
)

if(!$entrypoint) {

    $entrypoint = "bash"
}

docker-compose exec webapp $entrypoint $remainingArgs
