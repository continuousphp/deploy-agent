<?php

namespace CphpAgent\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CphpAgent\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project {
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
    private $name;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var Build
     * @ORM\OneToMany(targetEntity="\CphpAgent\Entity\Build",mappedBy="project", cascade={"persist","remove","detach","merge","refresh"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    protected $builds;


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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \CphpAgent\Entity\Build $builds
     */
    public function setBuilds($builds)
    {
        $this->builds = $builds;
    }

    /**
     * @return \CphpAgent\Entity\Build
     */
    public function getBuilds()
    {
        return $this->builds;
    }

    /**
     * @param \CphpAgent\Entity\DateTime $created
     */
    public function setCreated()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @return \CphpAgent\Entity\DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \CphpAgent\Entity\ExcludedFile $excludedFiles
     */
    public function setExcludedFiles($excludedFiles)
    {
        $this->excludedFiles = $excludedFiles;
    }

    /**
     * @return \CphpAgent\Entity\ExcludedFile
     */
    public function getExcludedFiles()
    {
        return $this->excludedFiles;
    }

    /**
     * @param \CphpAgent\Entity\DateTime $updated
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime("now");
    }

    /**
     * @return \CphpAgent\Entity\DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }


} 