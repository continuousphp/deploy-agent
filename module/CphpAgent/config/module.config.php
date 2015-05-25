<?php

return
[
    'controllers' =>
    [
        'invokables' => 
        [
            'CphpAgent\Index' => 'CphpAgent\Controller\IndexController'
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
            'CphpAgent\Form\Login'      => 'CphpAgent\Form\Login',
            'cphp-agent.service.deploy-manager' => 'CphpAgent\Service\DeployManager',
            'cphp-agent.service.user'   => 'CphpAgent\Service\User',
            'cphp-agent.mapper.build'   => 'CphpAgent\Mapper\Build',
            'cphp-agent.mapper.user'    => 'CphpAgent\Mapper\User',
        ],
        'factories' =>
        [
            'cphp-agent.logger' => 'CphpAgent\Factory\AgentLoggerFactory',
            'cphp-agent.login.form' => 'CphpAgent\Factory\Form\LoginFormFactory',
        ],
        'abstract_factories' =>
        [
            'CphpAgent\Mapper\AbstractFactory',
            'CphpAgent\Service\AbstractFactory',
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'initializers' =>
        [
            'CphpAgent\Mapper\Initializer',
        ],
    ],
    'doctrine' =>
    [
        'driver' =>
        [
            'cphpagent_driver' =>
            [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [ dirname(__DIR__) . '/src/CphpAgent/Entity' ]
            ],
            'orm_default' =>
            [
                'drivers' =>
                [
                    'CphpAgent\Entity' => 'cphpagent_driver',
                ]
            ]
        ],
        'authentication' =>
        [
            'orm_default' =>
            [
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'CphpAgent\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
                'credential_callable' => 'CphpAgent\Service\User::verifyHashedPassword'
            ],
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
                        'controller' => 'CphpAgent\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
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
            'layout/layout'     => __DIR__ . '/../view/layout/layout.phtml',
            'cphp-agent/index/index' => __DIR__ . '/../view/cphp-agent/index/index.phtml',
            'error/404'         => __DIR__ . '/../view/error/404.phtml',
            'error/index'       => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' =>
        [
            'CphpAgent' => __DIR__ . '/../view',
        ],
    ],
];
