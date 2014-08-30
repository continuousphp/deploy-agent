<?php

namespace CphpAgent;

use CphpAgent\Service\AgentLogger;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use CphpAgent\Config\ConfigAwareInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof ConfigAwareInterface) {
                        $config = $sm->getServiceLocator()->get('Config');
                        $config = isset($config['deployAgent']) ? $config['deployAgent'] : array();
                        $instance->setConfig($config);
                    }
                }
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'cphpagent_deploymanager_service' => 'CphpAgent\Service\DeployManager',
            ),
            'factories' => array(
                'cphpagent_project_service' => 'CphpAgent\Factory\ProjectServiceFactory',

//                'cphpagent_logger_service' => function($sm) {
//                    $logger = new AgentLogger();
//                    $logger->initLogger($sm->get('Config')['deployAgent']['buildPath']);
//                    return $logger;
//                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
