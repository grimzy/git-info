<?php

namespace Grimzy\GitInfo\Tests;

use Grimzy\GitInfo\GitInfo;
use PHPUnit\Framework\TestCase;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GitInfoCommandTest extends TestCase
{
    /**
     * @var TemporaryDirectory $repo
     */
    private static $repo;

    /**
     * @var array $init
     */
    private static $init;

    public static function setUpBeforeClass(): void
    {
        self::createTestRepo();
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteTestRepo();
        parent::tearDownAfterClass();
    }

    private static function setTemporaryDirectory(): TemporaryDirectory
    {
        if (!self::$repo) {
            self::$repo = (new TemporaryDirectory())
                ->location(__DIR__)
                ->name('test-repo');
        }
        return self::$repo;
    }

    private static function createTestRepo()
    {
        $cwd = getcwd();
        self::setTemporaryDirectory();
        self::$repo->force()->create();

        // Go to working dir
        chdir(self::$repo->path());

        // Init test repo
        exec('git init', $r);
        exec('git config user.email "tests@git-info"', $r);
        exec('git config user.name "Test Suite"', $r);


        $file_path = self::$repo->path('file.txt');
        file_put_contents($file_path, 'a', FILE_APPEND);

        // Add and commit
        exec('git add -A');
        exec('git commit -m "Added letter a"', $r);
        exec('git tag -a v0.0.1 -m "Version 0.0.1"');
        exec('git tag -a -f latest -m "Added latest tag"');
        exec('git log', $r);

        // Go back to command's working dir before running tests
        chdir($cwd);

        self::$init = $r;
    }

    private static function deleteTestRepo()
    {
        self::setTemporaryDirectory();
        self::$repo->delete();
    }

    public function testReturnsLatestCommit()
    {
        $revision = explode(' ', self::$init[4]);
        $revision = trim(array_pop($revision));
        $subject = trim(self::$init[8]);
        $date = explode(' ', self::$init[6]);
        array_shift($date);
        $date = date('c', strtotime(trim(implode(' ', $date))));

        $path = self::$repo->path();
        $gitInfo = new GitInfo($path);
        $info = $gitInfo->getInfo(['latest-commit']);

        // Here we convert the date of the test commit to be UTC so it is the same as the system.
        $commitDate = str_replace('Date: ', '', $info['latest-commit'][2]);
        $tempDate = new \DateTime($commitDate);
        $tempDate->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $info['latest-commit'][2] = 'Date: ' . $tempDate->format('c');

        $latestCommit = [
            'latest-commit' => [
                0 => 'Revision: ' . $revision,
                1 => 'Author: Test Suite (tests@git-info)',
                2 => 'Date: ' . $date,
                3 => 'Subject: ' . $subject
            ]
        ];

        $this->assertEquals($latestCommit, $info);
    }

    public function testReturnsAllTags()
    {
        $path = self::$repo->path();
        $gitInfo = new GitInfo($path);
        $info = $gitInfo->getInfo(['all-tags']);
        $this->assertEquals([
            'all-tags' => [
                0 => 'latest',
                1 => 'v0.0.1'
            ]
        ], $info);
    }
}
