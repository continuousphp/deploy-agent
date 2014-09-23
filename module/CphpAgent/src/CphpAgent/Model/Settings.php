<?php
namespace CphpAgent\Model;


class Settings
{
    private $buildPath;
    private $projectPath;

    function __construct($buildPath, $projectPath)
    {
        $this->$buildPath = $buildPath;
        $this->projectPath = $projectPath;
    }

    /**
     * @return string path where the different build will be save
     */
    public function getBuildPath()
    {
        return $this->buildPath;
    }

    /**
     * @return string path to the workspace where to put project
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }



}