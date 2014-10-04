<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
return [
    'deployAgent' => [

        /**
         * Destination path for your project
         */
        'projectPath' => '/tmp/www/',

        /**
         * Path where every are conserved during deployments
         */
        'buildPath' => '/tmp/www/deploy_agent_builds/',

        /**
         * Key/Value array for Project Name / Destination folder
         */
        'projects' => [
            /* project name */
            'foo' =>
                [
                    /* folder name */
                    'folder' => 'bar',

                    /* Permanent resources paths */
                    'permanentResources' =>
                        [
                            /* source folder             => destination in project folder */
                            '/tmp/www/resources/bar/uploads/' => '/uploads',
                        ],
                ],
        ],


        /**
         * Settings for Agent Logger
         */
        'logger' => [
            'writers' => [
                'logfile' =>
                    [
                        'enabled' => true,
                        'filter' => \Zend\Log\Logger::DEBUG, // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                        'adapter' => '\Zend\Log\Writer\Stream',
                        'options' =>
                            [
                                'output' => __DIR__ . '/../../data/logs/',
                                'file' => 'deployments.log'
                            ]
                    ]
            ]
        ]
    ]
];