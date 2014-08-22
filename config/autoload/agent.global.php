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
        'destPath'=> '/tmp/deploy-agent/',
        'applicationName' => 'MyContinuousProject',
    )
);