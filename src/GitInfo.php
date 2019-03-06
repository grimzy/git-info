<?php

namespace Grimzy\GitInfo;

class GitInfo
{
    /**
     * @var
     */
    protected $path;
    protected static $registeredCommands = [
        'latest-commit' => 'git log --format="Revision: %H%nAuthor: %an (%ae)%nDate: %aI%nSubject: %s" -n 1',
        'all-tags'      => 'git tag',
        'commit-hash-long'  => 'git log -1 --pretty=%H',
        'commit-hash-short' => 'git log -1 --pretty=%h',
        'author-name'       => 'git log -1 --pretty=%aN',
        'author-email'      => 'git log -1 --pretty=%aE',
        'author-date'       => 'git log -1 --pretty=%aI',
        'subject'           => 'git log -1 --pretty=%s',
        'branch'            => 'git rev-parse --abbrev-ref HEAD',
        'version'           => 'git describe --always --tags --abbrev=0'
    ];

    /**
     * GitInfo constructor.
     *
     * @param string $path
     * @param array  $commands
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
     * @param string $name
     * @param string $command
     */
    public static function addCommand(string $name, $command)
    {
        self::$registeredCommands[$name] = $command;
    }

    /**
     * This method runs the commands and return the results.
     *
     * @param array $commands
     *
     * @return array
     */
    public function getInfo($commands = null)
    {
        $cwd = getcwd();
        chdir($this->getWorkingDirectory());

        $commandResult = [];
        // Only continue if we received any commands.
        if (!empty($commands)) {
            if(is_array($commands)) {
                foreach ($commands as $command) {
                    // Execute the command and save the result to our array of commands.
                    $commandResult[$command] = $this->executeCommand($command);
                }
            } else if(is_string($commands)) {
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
     * @param $command
     *
     * @return mixed
     */
    private function executeCommand($commandKey)
    {
        if(array_key_exists($commandKey, self::$registeredCommands)) {
            exec(self::$registeredCommands[$commandKey], $result);
            if(is_array($result)){
                if(count($result) === 1){
                    return $result[0];
                } else {
                    return $result;
                }
            }
        } else {
            throw new \Exception('Command: ' . $commandKey . ' not registered.');
        }
    }
}
