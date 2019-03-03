<?php

namespace Grimzy\GitInfo\Tests;

use Grimzy\GitInfo\GitInfo;
use PHPUnit\Framework\TestCase;

class GitInfoTest extends TestCase
{
    public function testConstructorWithDefaultsParametersSetPathToCurrentWorkingDir()
    {
        $cwd = getcwd();

        $gitInfo = new GitInfo();
        $this->assertEquals($cwd, $gitInfo->getWorkingDirectory());
    }

    public function testConstructorWithPathSetsPathProperly()
    {
        $path = './path/to/dir';
        $gitInfo = new GitInfo($path);
        $this->assertEquals($path, $gitInfo->getWorkingDirectory());
    }

    // TODO: add test with non-existing path

    public function testEmptyPathIsSetToCurrentWorkingDirectory()
    {
        $path = getcwd();
        $gitInfo = new GitInfo(null);
        $this->assertEquals($path, $gitInfo->getWorkingDirectory());

        $gitInfo = new GitInfo('');
        $this->assertEquals($path, $gitInfo->getWorkingDirectory());
    }

    public function testSettingCommandsFromConstructor()
    {
        $gitInfo = new GitInfo(null, ['commit-hash' => 'echo 123bcsS']);
        $commands = $gitInfo->getRegisteredCommands();
        $this->assertEquals([
            'latest-commit' => 'git log --format="Revision: %H%nAuthor: %an (%ae)%nDate: %aI%nSubject: %s" -n 1',
            'all-tags' => 'git tag',
            'commit-hash' => 'echo 123bcsS'
        ], $commands);
    }

    public function testAddCommand()
    {
        $gitInfo = new GitInfo();
        $commands = $gitInfo->getRegisteredCommands();
        $this->assertEquals([], $commands);

        GitInfo::addCommand('commit-hash2', 'echo 456aedL');
        $commands = $gitInfo->getRegisteredCommands();
        $this->assertEquals(['commit-hash2' => 'echo 456aedL'], $commands);
    }

    public function testReturnsGitInfoForSingleCommandAsString()
    {
        $gitInfo = new GitInfo();
        GitInfo::addCommand('commit-hash', 'echo 123bcsS');
        $info = $gitInfo->getInfo('commit-hash');
        $this->assertEquals('123bcsS', $info);
    }

    public function testReturnsGitInfoForSingleCommandAsArray()
    {
        $gitInfo = new GitInfo();
        GitInfo::addCommand('commit-hash', 'echo 123bcsS');
        $info = $gitInfo->getInfo(['commit-hash']);
        $this->assertEquals(['commit-hash' => '123bcsS'], $info);
    }

    public function testReturnsGitInfoForMultipleCommands()
    {
        $gitInfo = new GitInfo();
        GitInfo::addCommand('commit-hash', 'echo 123bcsS');
        GitInfo::addCommand('commit-hash2', 'echo 456aedL');
        $info = $gitInfo->getInfo(['commit-hash', 'commit-hash2']);
        $this->assertEquals([
            'commit-hash' => '123bcsS',
            'commit-hash2' => '456aedL'
        ], $info);
    }

    public function testThrowsExceptionWhenCommandIsNotRegistered()
    {
        $gitInfo = new GitInfo();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Command: does-not-exist not registered.');
        $gitInfo->getInfo(['does-not-exist']);
    }
}
