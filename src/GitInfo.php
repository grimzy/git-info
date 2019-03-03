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
    public function getInfo(array $commands = [])
    {
        $cwd = getcwd();
        chdir($this->getWorkingDirectory());

        $commandResult = [];
        // Only continue if we received any commands.
        if (!empty($commands)) {
            foreach ($commands as $command) {
                // Verify that the command is registered.
                if (array_key_exists($command, self::$registeredCommands)) {
                    // Execute the command and save the result to our array of commands.
                    exec(self::$registeredCommands[$command], $result);
                    $commandResult[$command] = $result;
                    $result = [];
                } else {
                    throw new \Exception('Command: ' . $command . ' not registered.');
                }
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
}
