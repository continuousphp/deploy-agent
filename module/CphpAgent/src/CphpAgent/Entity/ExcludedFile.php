<?php

namespace CphpAgent\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CphpAgent\Repository\ExcludedFileRepository")
 * @ORM\Table(name="excluded_files")
 */
class ExcludedFile {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $path;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="\CphpAgent\Entity\Project", inversedBy="excludedFiles", cascade={"persist","remove","detach","merge","refresh"})
     */
    private $project;

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
     * @param mixed $created
     */
    public function setCreated()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
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
     * @param \CphpAgent\Entity\Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return \CphpAgent\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
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