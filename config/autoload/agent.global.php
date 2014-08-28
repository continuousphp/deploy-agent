<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
    // 'http://github.com/zendframework/ZendSkeletonModule/tarball/master',
//'packageUrl' =>'http://dasmuse.com/deploy-agent.tar.gz',
return array(
    'deployAgent' => array(
        'projectPath'=> '/tmp/deploy-agent/',
        'buildPath' => '/deploy_agent_build/',
        'project' => array(
            'testing' => 'zendframework-ZendSkeletonModule-2349bf5',
            'deploy-agent' => 'deploy-agent'
        )
    )
);