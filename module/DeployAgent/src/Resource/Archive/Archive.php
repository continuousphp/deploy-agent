<?php
/**
 * Archive.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Archive.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Archive;

use Continuous\DeployAgent\Iterator\FilterIteratorAwareTrait;
use Continuous\DeployAgent\Resource\AbstractResource;
use Continuous\DeployAgent\Resource\Archive\Extractor\ExtractorInterface;
use Continuous\DeployAgent\Resource\Archive\Extractor\TarGzExtractor;
use Continuous\DeployAgent\Resource\FileSystem\Directory;
use Continuous\DeployAgent\Workspace\Workspace;

/**
 * Archive
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Archive extends AbstractResource
{
    use FilterIteratorAwareTrait;

    /**
     * @var \SplFileInfo
     */
    protected $archive;

    /**
     * @var ExtractorInterface
     */
    protected $strategy;

    /**
     * @param string $pathname
     */
    public function __construct($pathname = null)
    {
        if (null !== $pathname) {
            $this->setPathname($pathname);
        }
    }

    /**
     * @param Workspace $workspace
     *
     * @return mixed
     */
    public function receive(Workspace $workspace)
    {
        $result = $this->getStrategy()->validate($workspace);
        if (!$result->isValid()) {
            throw new \InvalidArgumentException($result->getMessage());
        }
        if (null !== $this->archive) {
            rename($workspace->getPathname(), $this->archive->getPathname());
        } else {
            $this->archive = $workspace;
        }
    }

    /**
     * @return Workspace
     * @throws \Exception
     */
    public function fetch()
    {
        try {
            $destination = $this->getTempDirectory();
            $this->getStrategy()->extract($this->archive, $destination);
        } catch (\Exception $ex) {
            throw $ex;
        }

        $directory = new Directory($destination->getPathname());
        $directory->setEventManager($this->getEventManager());
        $directory->setFilters($this->iteratorFilters);

        return $directory->fetch();
    }

    /**
     * @return \SplFileInfo
     */
    protected function getTempDirectory()
    {
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('deploy-agent_' . time() . '_');
        if (! file_exists($tmp)) {
            mkdir($tmp, 0775, true);
        }
        return new \SplFileInfo($tmp);
    }


    /**
     * @todo: guess correct algorithm based on extension ?
     * @return ExtractorInterface
     */
    public function getStrategy()
    {
        if (null === $this->strategy) {
            $this->setStrategy(new TarGzExtractor());
        }
        return $this->strategy;
    }

    /**
     * @param ExtractorInterface $strategy
     *
     * @return self
     */
    public function setStrategy(ExtractorInterface $strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * @param $archive
     */
    public function setPathname($archive)
    {
        try {
            $archive = new \SplFileInfo($archive);
        } catch (\Exception $ex) {
            throw new \InvalidArgumentException('An error occur', 0, $ex);
        }
        if ($archive->isFile()) {
            unlink($archive->getPathname());
        }

        $this->archive = $archive;
    }
}
