<?php

namespace CphpAgent\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Build Entity
 *
 * @package     CphpAgent
 * @subpackage  Entity
 * @author      Daniel Leivas <daniel@dasmuse.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 *
 * @ORM\Entity(repositoryClass="CphpAgent\Repository\BuildRepository")
 * @ORM\Table(name="builds")
 */
class Build extends EntityAbstract {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=145, nullable=false)
     */
    private $path;

    /**
     * @var string
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

//    /**
//     * @var Project
//     * @ORM\ManyToOne(targetEntity="\CphpAgent\Entity\Project", inversedBy="builds", cascade={"persist","remove","detach","merge","refresh"})
//     */
//    private $project;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \CphpAgent\Entity\Project $project
     */
//    public function setProject($project)
//    {
//        $this->project = $project;
//    }
//
//    /**
//     * @return \CphpAgent\Entity\Project
//     */
//    public function getProject()
//    {
//        return $this->project;
//    }

} 