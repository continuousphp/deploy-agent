<?php

return [
    'controllers' => [
        'invokables' => 
            [
                'CphpAgent\Controller\Agent' => 'CphpAgent\Controller\AgentController'
            ] 
    ],
    'service_manager' => [
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
            'cphpagent_driver' =>
                [
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' =>
                        [
                            dirname(__DIR__) . '/src/CphpAgent/Entity',
                        ]
                ],
            'orm_default' =>
                [
                    'drivers' =>
                        [
                            'CphpAgent\Entity' => 'cphpagent_driver',
                        ]
                ]
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
                    'agent' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/agent',
                            'defaults' => [
                                'controller' => 'CphpAgent\Controller\Agent',
                                'action'     => 'admin',
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
