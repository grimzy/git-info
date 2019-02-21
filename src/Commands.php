<?php

include_once __DIR__.'/../vendor/autoload.php';

/**
 * Only execute if the request is from the CLI
 */

if(php_sapi_name() === 'cli')
{
    // Get the provider requested from the Cli.
    if(empty($argv[1]))
    {
        die("Please specify a provider such as: Git\n");
    }

    // Verify that the provider exists.
    if(!class_exists('Grimzy\GitInfo\\'.ucfirst($argv[1])))
    {
        die("There is no provider: \"$argv[1]\"\n");
    }

    $className = 'Grimzy\GitInfo\\'.ucfirst($argv[1]);
    $provider = new $className();

    // The command method will always exists in the provider in order to fullfil calls from the Cli.
    $provider::command($argv);
}
