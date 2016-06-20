<?php
/**
 * TaskManager.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TaskManager.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task;

use Continuous\DeployAgent\Agent\Agent;
use Continuous\DeployAgent\Application\Application;
use Continuous\DeployAgent\Event\DeployEvent;
use Continuous\DeployAgent\Provider\Continuousphp;
use Continuous\DeployAgent\Resource\Archive\Archive;
use Continuous\DeployAgent\Resource\FileSystem\Directory;
use Continuous\DeployAgent\Task\Runner\TaskRunnerManager;
use League\Flysystem\Filesystem;
use Zend\Config\Config;
use Zend\Console\ColorInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * TaskManager
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class TaskManager implements ListenerAggregateInterface
{

    /**
     * @var ConsoleAdapter
     */
    protected $console;
    
    /**
     * @var Application
     */
    protected $application;
    
    protected $build;
    
    /** @var Config */
    protected $config;
    
    protected $listeners = [];
    
    protected $packageFileSystem;
    
    protected $packageStoragePath;
    
    /** @var  EventManagerInterface */
    protected $eventManager;
    
    /** @var  TaskRunnerManager */
    protected $taskRunnerManager;

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     * @return TaskManager
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackageFileSystem()
    {
        return $this->packageFileSystem;
    }

    /**
     * @param Filesystem $packageFileSystem
     * @return TaskManager
     */
    public function setPackageFileSystem(Filesystem $packageFileSystem)
    {
        $this->packageFileSystem = $packageFileSystem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackageStoragePath()
    {
        return $this->packageStoragePath;
    }

    /**
     * @param mixed $packageStoragePath
     * @return TaskManager
     */
    public function setPackageStoragePath($packageStoragePath)
    {
        $this->packageStoragePath = $packageStoragePath;
        return $this;
    }

    /**
     * @return ConsoleAdapter
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * @param ConsoleAdapter $console
     * @return TaskManager
     */
    public function setConsole($console)
    {
        $this->console = $console;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @param mixed $build
     * @return TaskManager
     */
    public function setBuild($build)
    {
        $this->build = $build;
        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param EventManagerInterface $eventManager
     * @return TaskManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * @return TaskRunnerManager
     */
    public function getTaskRunnerManager()
    {
        if (empty($this->taskRunnerManager)) {
            $this->setTaskRunnerManager(new TaskRunnerManager());
        }
        return $this->taskRunnerManager;
    }

    /**
     * @param TaskRunnerManager $taskRunnerManager
     * @return TaskManager
     */
    public function setTaskRunnerManager(TaskRunnerManager $taskRunnerManager)
    {
        $this->taskRunnerManager = $taskRunnerManager;
        return $this;
    }
    
    /**
     * @param Config $config
     * @return TaskManager
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->setEventManager($events);
        $this->listeners[] = $events->attach(Application::EVENT_INSTALL, [$this, 'install']);
        $this->listeners[] = $events->attach(Application::EVENT_ACTIVATE, [$this, 'activate']);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function install(EventInterface $event)
    {
        $build = $event->getParam('build');
        /** @var Application $application */
        $application = $event->getTarget();
        $this->setApplication($application);
        $this->setBuild($build);
        
        /** @var Continuousphp $provider */
        $provider = $application->getProvider();
        $origin = $provider->getSource($build);
        $origin->setFilename($build . '.tar.gz');

        $origin->getEventManager()->attach(
            DeployEvent::EVENT_FETCH_PRE,
            function (DeployEvent $event) {
                $this->getConsole()
                    ->writeLine('Downloading package...', ColorInterface::LIGHT_CYAN);
            }
        );

        $this->getPackageFileSystem()->createDir($application->getName());
        $resourcePath = $this->getPackageStoragePath()
            . DIRECTORY_SEPARATOR . $application->getName()
            . DIRECTORY_SEPARATOR . $build . '.tar.gz';

        $resource = new Archive($resourcePath);

        $resource->getEventManager()->attach(
            DeployEvent::EVENT_FETCH_PRE,
            function () {
                $this->getConsole()
                    ->writeLine('Extracting package...', ColorInterface::LIGHT_CYAN);
            }
        );

        $destination = new Directory($application->getPath() . DIRECTORY_SEPARATOR . $build);

        $destination->getEventManager()->attach(
            DeployEvent::EVENT_RECEIVE_POST,
            function () use ($application) {
                $application->getEventManager()->trigger($application::EVENT_ACTIVATE);
            }
        );

        $agent = new Agent();
        $agent->setSource($origin)->setResource($resource)->setDestination($destination);
        $agent->deploy();
    }
    
    public function activate(EventInterface $event)
    {
        $application = $this->getApplication();
        $build = $this->getBuild();

        $configFile = $application->getPath()
            . DIRECTORY_SEPARATOR . $build
            . DIRECTORY_SEPARATOR . 'continuous.yml';

        if (file_exists($configFile)) {
            $this->loadConfig($configFile);
        }

        $application->getEventManager()->trigger($application::EVENT_AFTER_INSTALL);

        if (file_exists($application->getPath() . DIRECTORY_SEPARATOR . 'current')) {
            unlink($application->getPath() . DIRECTORY_SEPARATOR . 'current');
        }

        $application->getEventManager()->trigger($application::EVENT_BEFORE_ACTIVATE);

        $this->getConsole()
            ->writeLine(
                'Starting ' . $application->getName() . ' (' . $build . ')',
                ColorInterface::LIGHT_CYAN
            );

        symlink(
            $application->getPath() . DIRECTORY_SEPARATOR . $build,
            $application->getPath() . DIRECTORY_SEPARATOR . 'current'
        );

        $application->getEventManager()->trigger($application::EVENT_AFTER_ACTIVATE);

        $this->getConsole()
            ->writeLine(
                $application->getName() . ' (' . $build . ') has successfully started',
                ColorInterface::LIGHT_CYAN
            );
    }
    
    public function loadConfig($path)
    {
        $this->getConsole()->writeLine('Applying config from ' . $path, ColorInterface::WHITE);
        $configReader = new \Zend\Config\Reader\Yaml(['Symfony\Component\Yaml\Yaml', 'parse']);
        $config = $configReader->fromFile($path);
        $this->config = $config;
        if (isset($config['deployment']) && isset($config['deployment']['hooks'])) {
            foreach ($config['deployment']['hooks'] as $eventname => $tasks) {
                foreach ($tasks as $task) {
                    if (!isset($task['task-runner'])) {
                        $runner = $this->getTaskRunnerManager()->get('command');
                    } else {
                        $runner = $this->getTaskRunnerManager()->get($task['task-runner']);
                    }
                    $hydrator = new ClassMethods();
                    $hydrator->hydrate($task, $runner);
                    $this->listeners[] = $this->getEventManager()->attach($eventname, [$runner, 'run']);
                }
            }
        }
    }
}