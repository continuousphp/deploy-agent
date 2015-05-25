<?php
/**
 * Module.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Module.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * Module
 *
 * @package   Continuous\DeployAgent
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface
{
    /**
     * This method is defined in ConsoleBannerProviderInterface
     */
    public function getConsoleBanner(Console $console)
    {
        return 'Continuous Deployment Agent';
    }

    /**
     * This method is defined in ConsoleUsageProviderInterface
     */
    public function getConsoleUsage(Console $console)
    {
        return
            [
                'list applications' => 'List all registered applications',
                'add application' => 'register a new application',
            ];
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return
            [
                'Zend\Loader\StandardAutoloader' =>
                [
                    'namespaces' =>
                    [
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ],
                ],
            ];
    }
}
