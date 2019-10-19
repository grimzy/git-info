<?php

namespace Grimzy\GitInfo;

use Exception;

/**
 * Class GitInfo.
 *
 * @category Utility
 * @package  Grimzy\GitInfo
 * @author   Joseph Estefane <estefanejoe@gmail.com>
 * @author   Numa Quevedo <numaquevedo1@gmail.com>
 * @license  MIT https://github.com/grimzy/git-info/blob/master/LICENSE
 * @link     https://github.com/grimzy/git-info
 */
class GitInfo
{
    protected $path;

    /**
     * Associative array of <command-name, command> holding all registered commands.
     * Starts with predefined set of commands.
     *
     * @var string[]
     */
    protected static $registeredCommands = [
        'latest-commit' =>
            'git log --format="Revision: %H%nAuthor: %an (%ae)%n'
            . 'Date: %aI%nSubject: %s" -n 1',
        'all-tags' => 'git tag',
        'commit-hash-long' => 'git log -1 --pretty=%H',
        'commit-hash-short' => 'git log -1 --pretty=%h',
        'author-name' => 'git log -1 --pretty=%aN',
        'author-email' => 'git log -1 --pretty=%aE',
        'author-date' => 'git log -1 --pretty=%aI',
        'subject' => 'git log -1 --pretty=%s',
        'branch' => 'git rev-parse --abbrev-ref HEAD',
        'version' => 'git describe --always --tags --abbrev=0'
    ];

    /**
     * GitInfo constructor.
     *
     * @param string|null $path     The working directory. Where we run the commands.
     *                              Defaults to getcwd()
     * @param string[]    $commands List of commands to add to
     *                              GitInfo::$registeredCommands
     */
    public function __construct($path = null, array $commands = [])
    {
        $this->path = !empty($path) ? $path : getcwd();

        // Register commands provided.
        if (!empty($commands)) {
            foreach ($commands as $name => $command) {
                self::addCommand($name, $command);
            }
        }
    }

    /**
     * Add a custom command to $registeredCommands for later use.
     *
     * @param string $name    The command's name
     * @param string $command The command
     *
     * @return void
     */
    public static function addCommand(string $name, $command)
    {
        self::$registeredCommands[$name] = $command;
    }

    /**
     * Get all registered commands.
     *
     * @return array
     */
    public static function getRegisteredCommands()
    {
        return self::$registeredCommands;
    }

    /**
     * Run the command(s) provided, and returns the results.
     * If none are provided, runs all registered commands.
     *
     * @param string|string[]|null $names Command name or list of command
     *                                    names
     *
     * @return string|string[]|null
     * @throws \Exception
     */
    public function getInfo($names = null)
    {
        $cwd = getcwd();
        chdir($this->getWorkingDirectory());

        $commandResult = $this->executeAllCommands($names);

        chdir($cwd);

        return $commandResult;
    }

    /**
     * Retrieve the working directory.
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return $this->path;
    }

    /**
     * Executes all commands provided in $names.
     * If no command name is provided, then run all registered commands.
     *
     * @param string|string[]|null $names Command name or list of
     *                                    commands names to run
     *
     * @return string|string[]|null
     * @throws Exception
     */
    private function executeAllCommands($names = null)
    {
        if (empty($names)) {
            return $this->executeRegisteredCommands();
        }

        if (is_array($names)) {
            return $this->executeCommands($names);
        } elseif (is_string($names)) {
            return $this->executeCommand($names);
        }

        return null;
    }

    /**
     * Executes all registered commands.
     *
     * @return string[]
     * @throws Exception
     */
    private function executeRegisteredCommands()
    {
        $commandResult = [];
        foreach (self::$registeredCommands as $name => $command) {
            $commandResult[$command] = $this->executeCommand($name);
        }
        return $commandResult;
    }

    /**
     * Executes all commands provided in $names.
     *
     * @param string[] $names List of commands names
     *
     * @return string[]
     * @throws Exception
     */
    private function executeCommands(array $names)
    {
        $commandResult = [];
        foreach ($names as $name) {
            $commandResult[$name] = $this->executeCommand($name);
        }
        return $commandResult;
    }

    /**
     * Execute the given command and returns the result.
     *
     * @param string $name The name of the command to execute
     *
     * @return string|string[]
     * @throws \Exception
     */
    private function executeCommand($name)
    {
        if (array_key_exists($name, self::$registeredCommands)) {
            exec(self::$registeredCommands[$name], $results);
            return $this->processCommandResults($results);
        }
        throw new Exception('Command: ' . $name . ' not registered.');
    }

    /**
     * This method returns the results from a command.
     * When $result has only one line, return as string.
     *
     * @param string[] $results The results from running a command
     *
     * @return string|string[]
     */
    private function processCommandResults(array $results)
    {
        if (count($results) === 1) {
            return $results[0];
        }
        return $results;
    }
}
