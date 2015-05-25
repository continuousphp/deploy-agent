<?php

return
[
    'controllers' =>
    [
        'invokables' => 
        [
            'DeployAgent\Index' => 'Continuous\DeployAgent\Controller\IndexController',
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
        ],
        'shared' =>
        [
            'provider/continuousphp',
            'application/application',
        ]
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
                        'route' => 'add application',
                        'defaults' =>
                        [
                            'controller' => 'DeployAgent\Application',
                            'action' => 'add'
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
    
];
