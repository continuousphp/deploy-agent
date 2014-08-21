<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */

return array(
    'deployAgent' => array(
        'destPath'=> '/deploy-agent/',
        'packageUrl' => 'http://github.com/zendframework/ZendSkeletonModule/tarball/master',
        'applicationName' => 'MyContinuousProject',
    )
);