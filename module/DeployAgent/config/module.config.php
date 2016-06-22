<?php

return
[
    'controllers' =>
    [
        'invokables' => 
        [
            'DeployAgent\Index' => 'Continuous\DeployAgent\Controller\IndexController',
            'DeployAgent\Hook' => 'Continuous\DeployAgent\Controller\HookController',
            'DeployAgent\Application' => 'Continuous\DeployAgent\Controller\ApplicationController'
        ]
    ],
    'service_manager' =>
    [
        'aliases' =>
        [
            'entity_manager' => 'Doctrine\ORM\EntityManager',
        ],
        'invokables' =>
        [
            'provider/continuousphp' => 'Continuous\\DeployAgent\\Provider\\Continuousphp',
            'application/application' => 'Continuous\\DeployAgent\\Application\\Application',
            'application/application-manager' => 'Continuous\\DeployAgent\\Application\\ApplicationManager',
            'doctrine.naming_strategy.underscore' => 'Doctrine\\ORM\\Mapping\\UnderscoreNamingStrategy'
        ],
        'factories' =>
        [
            'taskmanager' => 'Continuous\\DeployAgent\\Task\\TaskManagerFactory'
        ],
        'shared' =>
        [
            'provider/continuousphp' => false,
            'application/application' => false,
        ],
        'initializers' =>
        [
            'Continuous\\DeployAgent\\EntityManagerInitializer'
        ]
    ],
    'doctrine' =>
    [
        'driver' =>
        [
            'agent_driver' =>
            [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [ dirname(__DIR__) . '/src' ]
            ],
            'orm_default' =>
            [
                'drivers' =>
                [
                    'Continuous\DeployAgent' => 'agent_driver',
                ]
            ]
        ],
        'configuration' =>
        [
            'orm_default' =>
                [
                    'generate_proxies' => false,
                    'metadata_cache' => 'array',
                    'query_cache' => false,
                    'result_cache' => false,
                    'driver' => 'orm_default',
                    'naming_strategy' => 'doctrine.naming_strategy.underscore'
                ]
        ],
    ],
    'router' =>
    [
        'routes' =>
        [
            'home' =>
            [
                'type'    => 'Literal',
                'options' =>
                [
                    'route'    => '/',
                    'defaults' =>
                    [
                        'controller' => 'DeployAgent\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            'hook' =>
            [
                'type' => 'segment',
                'options' =>
                [
                    'route' => '/webhook/:buildProvider',
                    'defaults' =>
                    [
                        'controller' => 'DeployAgent\Hook',
                        'action'     => 'deploy'
                    ]
                ]
            ]
        ],
    ],
    'console' =>
    [
        'router' =>
        [
            'routes' =>
            [
                'list-application' =>
                [
                    'options' =>
                    [
                        'route' => 'list applications',
                        'defaults' =>
                        [
                            'controller' => 'DeployAgent\Application',
                            'action' => 'list'
                        ]
                    ]
                ],
                'add-application' =>
                [
                    'options' =>
                    [
                        'route' => 'add application [--provider=] [--token=] [--repository-provider=] [--repository=] '
                                 . '[--pipeline=] [--name=] [--path=]',
                        'defaults' =>
                        [
                            'controller' => 'DeployAgent\Application',
                            'action' => 'add'
                        ]
                    ]
                ],
                'deploy-application' =>
                [
                    'options' =>
                    [
                        'route' => 'deploy application [--name=] [--build=]',
                        'defaults' =>
                        [
                            'controller' => 'DeployAgent\Application',
                            'action' => 'deploy'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'view_manager' =>
    [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' =>
        [
            'layout/layout'          => __DIR__ . '/../view/layout/layout.phtml',
            'continuous/index/index' => __DIR__ . '/../view/agent/index.phtml',
            'error/404'              => __DIR__ . '/../view/error/404.phtml',
            'error/index'            => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' =>
        [
            'continuous' => __DIR__ . '/../view',
        ],
    ],
    'log' =>
    [
        'DeployLog' =>
        [
            'writers' =>
            [
                [
                    'name' => 'stream',
                    'options' =>
                    [
                        'stream' => './data/logs/deploy.log',
                    ],
                ],
            ],
        ],
    ]
];
