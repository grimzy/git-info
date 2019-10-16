<?php

namespace Grimzy\GitInfo;

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
     * Array holding all registered commands.
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
     * @param string|null $path     The current working directory.
     *                              Defaults to getcwd()
     * @param string[]    $commands List of commands to add to
     *                              GitInfo::$registeredCommands
     */
    public function __construct($path = null, array $commands = [])
    {
        if (!empty($path)) {
            $this->path = $path;
        } else {
            $this->path = getcwd();
        }

        // Register commands provided.
        if (!empty($commands)) {
            foreach ($commands as $commandName => $command) {
                self::addCommand($commandName, $command);
            }
        }
    }

    /**
     * Add custom commands.
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
     * This method runs the commands and return the results.
     *
     * @param string|string[]|null $commands Command or list of commands to run
     *                                       If null, runs all registered commands
     *
     * @return string|array
     * @throws \Exception
     */
    public function getInfo($commands = null)
    {
        $cwd = getcwd();
        chdir($this->getWorkingDirectory());

        $commandResult = [];
        // Only continue if we received any commands.
        if (!empty($commands)) {
            if (is_array($commands)) {
                foreach ($commands as $command) {
                    // Execute the command and save the result to results.
                    $commandResult[$command] = $this->executeCommand($command);
                }
            } elseif (is_string($commands)) {
                return $this->executeCommand($commands);
            }
        } else {
            // Execute all the commands registered.
            foreach (self::$registeredCommands as $commandKey => $command) {
                $commandResult[$command] = $this->executeCommand($commandKey);
            }
        }
        chdir($cwd);

        return $commandResult;
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
     * Retrieve the working directory.
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return $this->path;
    }

    /**
     * This method executes the given command and returns the result.
     *
     * @param string $name The name of the command to execute
     *
     * @return mixed
     * @throws \Exception
     */
    private function executeCommand($name)
    {
        if (array_key_exists($name, self::$registeredCommands)) {
            exec(self::$registeredCommands[$name], $result);
            if (is_array($result)) {
                if (count($result) === 1) {
                    return $result[0];
                } else {
                    return $result;
                }
            }
        }
        throw new \Exception('Command: ' . $name . ' not registered.');
    }
}
