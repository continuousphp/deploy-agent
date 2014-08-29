<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 27/08/14
 * Time: 10:23
 */

namespace CphpAgent\Deploy\Adapter;


use Agent\Service\AgentLogger;

class Phing {

    public static function Execute($projectFolder,$args=null)
    {
        if(is_file($projectFolder.'build.xml')){
            AgentLogger::info('Phing script');
            $escapeArgs = '';
            if(is_array($args)){
                foreach($args as $propName => $value){
                    $escape = escapeshellarg(' -D'.$propName.'='.$value);
                    $escapeArgs = $escapeArgs . $escape;
                }
            }
            $lastDir = getcwd();
            chdir($projectFolder);
            AgentLogger::info('  Command: '.'phing'.$escapeArgs);
            $result = shell_exec('phing'.$escapeArgs);
            AgentLogger::info($result);
            chdir($lastDir);
            AgentLogger::info('Phing script [done]');
        }
    }
} 