<?php

namespace Grimzy\GitInfo;


class Git extends AbstractBase
{
    /**
     * Git constructor.
     * @param string $defaultFilePath
     */
    public function __construct($defaultFilePath = '')
    {
        parent::__construct($defaultFilePath);
    }

    /**
     * Read the repository information.
     *
     * @return mixed
     */
    public function getInfo($saveToFile = false, $fileName = '')
    {
        //$
    }

    /**
     * Write the information retrieved from the repository in the cache file.
     */
    public function writeInfo($fileName)
    {
        // TODO
    }

    protected function readFromCacheFile()
    {
        //return self::command();
    }

    /**
     * Executes a pre-set command against Git using the command line.
     *
     * @param $name
     * @param $command
     * @return mixed|void
     */
    public static function command($args)
    {
        self::parseCommandArguments($args);
        /*$commandToExecute = '';
        switch($name)
        {
            case 'latest':
                $commandToExecute = 'git log --format="Revision: %H%nAuthor: %an (%ae)%nDate: %aI%nSubject: %s" -n 1';
                break;

            default:
                // TODO
                break;
        }

        if(!empty($commandToExecute))
        {
            return shell_exec($commandToExecute);
        }*/
    }
}