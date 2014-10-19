<?php

return [
    'controllers' => [
        'invokables' => 
            [
                'CphpAgent\Controller\Agent' => 'CphpAgent\Controller\AgentController',
                'CphpAgent\Controller\Admin' => 'CphpAgent\Controller\AdminController'
            ]
    ],
    'service_manager' => [
        'aliases' =>
            [
                'entity_manager' => 'Doctrine\ORM\EntityManager',
            ],
        'invokables' =>
            [
                'cphp-agent.service.deploy-manager' => 'CphpAgent\Service\DeployManager',
                'cphp-agent.service.user' => 'CphpAgent\Service\User',
                'cphp-agent.mapper.build' => 'CphpAgent\Mapper\Build',
                'cphp-agent.mapper.user' => 'CphpAgent\Mapper\User',
            ],
        'factories' =>
            [
                'cphp-agent.logger' => 'CphpAgent\Factory\AgentLoggerFactory',
            ],
        'abstract_factories' =>
            [
                'CphpAgent\Mapper\AbstractFactory',
                'CphpAgent\Service\AbstractFactory',
            ],
        'initializers' =>
            [
                'CphpAgent\Mapper\Initializer',
            ],
    ],
    'doctrine' => [
        'driver' => [
            'cphpagent_driver' => [
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' =>
                        [
                            dirname(__DIR__) . '/src/CphpAgent/Entity',
                        ]
            ],
            'orm_default' => [
                    'drivers' =>
                        [
                            'CphpAgent\Entity' => 'cphpagent_driver',
                        ]
            ]
        ],
        'authentication' => [
            'orm_default' =>
                [
                    'object_manager' => 'Doctrine\ORM\EntityManager',
                    'identity_class' => 'CphpAgent\Entity\User',
                    'identity_property' => 'username',
                    'credential_property' => 'password',
//                    'credential_callable' => 'CphpAgent\Service\User::verifyHashedPassword'
                ],
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/[/:action]',
                    'defaults' => [
                        'controller' => 'CphpAgent\Controller\Agent',
                        'action'     => 'index',
                    ],
                ],
            ],
            'zfcadmin' => [
                'child_routes' => [
                    'login' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/login',
                            'defaults' => [
                                'controller' => 'CphpAgent\Controller\Admin',
                                'action'     => 'login',
                            ],
                        ],
                    ],
                    'deployments' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/deployments',
                            'defaults' => [
                                'controller' => 'CphpAgent\Controller\Admin',
                                'action'     => 'deployments',
                            ],
                        ],
                    ],
                    'add-user' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/add-user',
                            'defaults' => [
                                'controller' => 'CphpAgent\Controller\Admin',
                                'action'     => 'addUser',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' =>
            [
                'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
                'agent/agent/index' => __DIR__ . '/../view/agent/agent/index.phtml',
                'error/404'               => __DIR__ . '/../view/error/404.phtml',
                'error/index'             => __DIR__ . '/../view/error/index.phtml',
            ],
        'template_path_stack' =>
            [
                'CphpAgent' => __DIR__ . '/../view',
            ],
    ],
];
