<?php

namespace Grimzy\GitInfo;

abstract class AbstractBase
{
    /**
     * @var $defaultFilePath
     */
    protected static $defaultFilePath;

    /**
     * AbstractBase constructor.
     * @param string $defaultFilePath
     */
    public function __construct($defaultFilePath = '')
    {
        if(!empty($defaultFilePath))
        {
            self::$defaultFilePath = $defaultFilePath;
        }
    }

    /**
     * This method reads the information from git and either return it or save it to a file.
     *
     * @param bool $saveToFile
     * @param string $fileName
     * @return mixed
     */
    abstract public function getInfo($saveToFile = false, $fileName = '');


    /**
     * This method writes the information retrieved from git to a file.
     *
     * @param $fileName
     * @return mixed
     */
    abstract protected function writeInfo($fileName);

    /**
     * This method executes a command to get the repository information.
     *
     * @param $name
     * @param $command
     * @return mixed
     */
    abstract protected static function command($args);

    /**
     * Method to parse the command and the arguments passed.
     *
     * @param $command
     * @param $arguments
     * @return mixed
     */
    protected function parseCommandArguments($args)
    {
        /**
         * If we got here, we do not need to check for the values of $args[0] and $args[1] since those are the script
         * name and the provider class respectively. We just need to check for $args[2] and up.
         */
        $parsedArguments = [];
        if(!empty($args[2]))
        {
            if(strpos($args[2], ':') !== false)
            {
                $parsedCommand = explode(':', $args[2]);
                $parsedArguments[$parsedCommand[0]] = $parsedCommand[1];
            }
        }
    }
}