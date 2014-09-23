<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
return array(
    'deployAgent' => array(
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
        'project' => array(
            'testing' => 'zendframework-ZendSkeletonModule-2349bf5',
            'deploy-agent' => 'testing-agent'
        ),

        /**
         * Settings for Agent Logger
         */
        'logger' => array(
            'writers' => array(
                'logfile' => array(
                    'enabled' => true,
                    'filter' => \Zend\Log\Logger::DEBUG, // options: EMERG, ALERT, CRIT, ERR, WARN, NOTICE, INFO, DEBUG
                    'adapter' => '\Zend\Log\Writer\Stream',
                    'options' => array(
                        'output' => __DIR__ . '/../../data/logs/',
                        'file' => 'deployments.log'
                    ),
                )
            )
        )
    )
);