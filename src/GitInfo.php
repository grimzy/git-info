<?php

namespace Grimzy\GitInfo;


class GitInfo
{
    /**
     * @var
     */
    protected $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function getWorkingDirectory()
    {
        return $this->path;
    }
}
