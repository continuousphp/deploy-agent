<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:21
 */

namespace CphpAgent\Model;


class Project {
    private $repoName;
    private $projectName;
    private $excludeFolder;
    private $phingParam;

    function __construct($repoName, $projectName)
    {
        $this->$repoName = $repoName;
        $this->$projectName = $projectName;
    }

    /**
     * @return string name of the project. (also name of the folder in workspace)
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string name of the repository where the project is.
     */
    public function getRepoName()
    {
        return $this->repoName;
    }
} 