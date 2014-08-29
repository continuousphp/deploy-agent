<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CphpAgent;

use Agent\Model\Deployment;
use Agent\Model\DeploymentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

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
                'agent_deploymanager_service' => 'CphpAgent\Service\DeployManager',
            ),
            'factories' => array(
                'agent_logger_service' => function($sm) {
                    $logger = new \Agent\Service\AgentLogger();
                    $logger->initLogger($sm->get('Config')['deployAgent']['buildPath']);
                    return $logger;
                },
                'CphpAgent\Model\DeploymentTable' => function ($sm) {
                        $tableGateway = $sm->get('DeploymentTableGateway');
                        $table = new DeploymentTable($tableGateway);
                        return $table;
                    },
                'DeploymentTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Deployment());
                        return new TableGateway('deployment', $dbAdapter, null, $resultSetPrototype);
                    },
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
