<?php
/**
 * Directory.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Directory.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\FileSystem;

use Continuous\DeployAgent\Event\DeployEvent;
use Continuous\DeployAgent\Exception\FileSystemException;
use Continuous\DeployAgent\Iterator\FilterIteratorAwareTrait;
use Continuous\DeployAgent\Iterator\RecursiveDirectoryAggregate;
use Continuous\DeployAgent\Resource\AbstractResource;
use Continuous\DeployAgent\Workspace\Workspace;
use Zend\Stdlib\ErrorHandler;

/**
 * Directory
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Directory extends AbstractResource
{
    use FilterIteratorAwareTrait;

    /**
     * @var \SplFileInfo
     */
    protected $directory;

    /**
     * @var int
     */
    protected $dirCreateMode = 0775;

    /**
     * Convert this to setter...
     *
     * @param string $directory
     * @throws \InvalidArgumentException
     */
    public function __construct($directory = null)
    {
        if (null !== $directory) {
            $this->setPathname($directory);
        }
    }

    /**
     * @param $directory
     *
     * @return $this
     */
    public function setPathname($directory)
    {
        try {
            $directory = new \SplFileInfo($directory);
        } catch (\Exception $ex) {
            throw new \InvalidArgumentException('An error occur', 0, $ex);
        }
        
        if ($directory->isFile()) {
            throw new \InvalidArgumentException('Expecting a directory, got file');
        }
        
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return Workspace
     */
    public function fetch()
    {
        $events = $this->getEventManager();
        $event  = new DeployEvent(null, $this);
        $events->trigger(DeployEvent::EVENT_FETCH_PRE, $event);

        $iterator = new RecursiveDirectoryAggregate($this->directory);
        $this->getFilterIterator($iterator);

        $build = new Workspace($this->directory->getPathname());
        $build->setIterator($iterator->getIterator());
        $event->setWorkspace($build);
        $events->trigger(DeployEvent::EVENT_FETCH_POST, $event);

        return $build;
    }

    /**
     * @param Workspace $build
     * @return mixed
     */
    public function receive(Workspace $build)
    {
        $iterator = $this->getFilterIterator($build->getIterator());
        $events   = $this->getEventManager();
        $event    = new DeployEvent(null, $this);

        $event->setWorkspace($build);
        $events->trigger(DeployEvent::EVENT_RECEIVE_PRE, $event);

        foreach ($iterator as $file) {
            try {
                $synced = $this->sync($build, $file);
                $event->setError(null);
                $event->setDestination($synced);
                $event->setName(DeployEvent::EVENT_RECEIVE);
            } catch (FileSystemException $e) {
                $event->setSource(null);
                $event->setError($e);
                $event->setName(DeployEvent::EVENT_RECEIVE_ERROR);
            }
            $event->setSource($file);
            $events->trigger($event);
        }

        $events->trigger(DeployEvent::EVENT_RECEIVE_POST);

        return $this->directory;
    }

    /**
     * todo: Trigger separates events on Directory / File creation ?
     *
     * @param Workspace    $workspace
     * @param \SplFileInfo $file
     *
     * @return \SplFileInfo
     * @throws FileSystemException
     * @throws mixed
     */
    protected function sync(Workspace $workspace, \SplFileInfo $file)
    {
        $buildPathname = $workspace->getPathname();
        $filePath      = $file->getPath();
        $basePath      = substr($filePath, strlen($buildPathname));
        $relativePath  = $this->directory->getPathname() . $basePath;

        if (is_dir($filePath) && ! is_dir($relativePath)) {
            try {
                ErrorHandler::start(E_WARNING);
                mkdir($relativePath, $this->dirCreateMode, true);
                ErrorHandler::stop(true);
            } catch (\ErrorException $e) {
                throw new FileSystemException($e->getMessage(), FileSystemException::CREATION_ERROR, $e);
            }
        }

        $destination = $relativePath . DIRECTORY_SEPARATOR . $file->getFilename();
        try {
            ErrorHandler::start(E_WARNING);
            copy($file->getPathname(), $destination);
            ErrorHandler::stop(true);
        } catch (\ErrorException $e) {
            throw new FileSystemException($e->getMessage(), FileSystemException::COPY_ERROR, $e);
        }

        return new \SplFileInfo($destination);
    }
}
