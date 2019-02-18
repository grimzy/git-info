<?php

namespace Grimzy\GitInfo\Tests;

use Grimzy\GitInfo\GitInfo;
use PHPUnit\Framework\TestCase;

class GitInfoTest extends TestCase
{

    public function testPathIsSet()
    {
        $path = './path/to/dir';
        $gitInfo = new GitInfo($path);
        $this->assertEquals($path, $gitInfo->getWorkingDirectory());
    }
}
