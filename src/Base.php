<?php

class Base
{
    /**
     * @var $defaultFilePath
     */
    protected static $defaultFilePath;

    /**
     * GitInfoBase constructor.
     */
    public function __construct()
    {
        self::$defaultFilePath = storage_path('app');
    }

    public function readGitInfo()
    {
        // Check if our generated git version file exists
        if (file_exists(self::$defaultFilePath . 'git-info.json')) {
            // Read the information from the existing git file.
            $gitInfoJsonFileContents = $this->readGitInfoJsonFile(self::$defaultFilePath . 'git-info.json');
            // TODO: Add configuration to modify how to return this information. Return a Blade snippet maybe (?)
            return $gitInfoJsonFileContents;
        } else {
            // When the file does not exists, we attempt to create it and return its contents.
            // TODO: Allow to configure this behavior from a config file.
            $this->getRepositoryInformation();
        }
    }

    /**
     * This method writes the 'cache' file that will hold the git information retrieved.
     */
    public function writeGitInfo()
    {
        // TODO
    }

    /**
     * This method executes the git command directly and returns the information.
     */
    private function getRepositoryInformation()
    {
        // TODO
    }

    /**
     * This method attempts to read the git information previously stored in the 'cache' file.
     *
     * @param $fileLocation
     * @return bool|string
     */
    private function readGitInfoJsonFile($fileLocation)
    {
        try {
            $fileContents = file_get_contents($fileLocation);
            return $fileContents;
        } catch(Exception $e) {
            return false;
        }
    }
}