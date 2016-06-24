<?php
/**
 * TaskManagerFactory.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TaskManagerFactory.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task;

use League\Flysystem\Filesystem;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;

/**
 * TaskManagerFactory
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class TaskManagerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TaskManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $taskManager = new TaskManager();
        
        if ($serviceLocator->has('Console')) {
            /** @var ConsoleAdapter $console */
            $console = $serviceLocator->get('Console');
            if ($console instanceof ConsoleAdapter) {
                $taskManager->setConsole($console);
            }
        }
        
        /** @var Logger $logger */
        $logger = $serviceLocator->get('logger/deploy');
        $taskManager->setLogger($logger);
        
        $taskManager->setPackageStoragePath($serviceLocator->get('Config')['agent']['package_storage_path'])
            ->setPackageFileSystem(
                new Filesystem($serviceLocator->get('BsbFlysystemAdapterManager')->get('packages'))
            );
        
        return $taskManager;
    }
}
