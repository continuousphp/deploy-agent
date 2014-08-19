<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Agent\Controller\Agent' => 'Agent\Controller\AgentController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'agent' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/agent[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
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
                                'action'     => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'agent' => __DIR__ . '/../view',
        ),
    ),
);
