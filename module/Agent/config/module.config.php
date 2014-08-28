<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Agent\Controller\Agent' => 'Agent\Controller\AgentController'
        ),
    ),
    'service_manager' => array(
        'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\Adapter',
        'Agent\Model\DeploymentTable' => 'Agent\Model\DeploymentTable'
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/[/:action]',
                    'defaults' => array(
                        'controller' => 'Agent\Controller\Agent',
                        'action'     => 'index',
                    ),
                ),
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'agent' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/agent',
                            'defaults' => array(
                                'controller' => 'Agent\Controller\Agent',
                                'action'     => 'admin',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'agent/agent/index' => __DIR__ . '/../view/agent/agent/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'agent' => __DIR__ . '/../view',
        ),
    ),
);
