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
                'Application management:',
                'list applications' => 'List all registered applications',
                'add application [--provider=] [--token=] [--repository-provider=] [--repository=] '
                . '[--pipeline=] [--name=] [--path=]' => 'register a new application',
                ['--provider=PROVIDER', 'The provider to use (ie: continuousphp)'], 
                ['--token=TOKEN', 'A valid token to consume the provider API'],
                [
                    '--repository-provider=REPOSITORY_PROVIDER',
                    'The repository provider to use (ie: git-hub, bitbucket...)'
                    . PHP_EOL . '(for continuousphp only)'
                ],
                ['--repository=REPOSITORY', 'The repository key to use (ie: continuousphp/deploy-agent)'], 
                [
                    '--pipeline=PIPELINE',
                    'The pipeline to use (ie: refs/heads/master)'
                    . PHP_EOL . '(for continuousphp only)'
                ],
                ['--name=NAME', 'The name of the application'],
                ['--path=PATH' => 'The destination path']
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
