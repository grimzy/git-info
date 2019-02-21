<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Grimzy\GitInfo\Git;

$git = new Git();
echo $git->getInfo();