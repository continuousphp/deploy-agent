<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
return [
    'bsb_flysystem' =>
    [
        'adapters' =>
        [
            'packages' =>
            [
                'type' => 'local',
                'options' =>
                [
                    'root' => './data/packages'
                ]
            ]
        ]
    ],
    
    'agent' => [

        'package_retention' => 2, // package retention per application
        'package_storage_path' => './data/packages', // flysystem adapter key to store package history

        /**
         * Destination path for your project
         */
        'projectPath' => '/tmp/www/',

        /**
         * Path where every are conserved during deployments
         */
        'buildPath' => '/tmp/www/deploy_agent_builds/',

        /* hexadecimal key to crypt/decrypt sensitive data */
        'hash-key' => 'f01ee0962998007d40c7ce32bfec773028785cadfa0064a467662cb87171012c',

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