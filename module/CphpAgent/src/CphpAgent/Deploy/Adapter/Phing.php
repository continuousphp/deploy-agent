<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 27/08/14
 * Time: 10:23
 */

namespace CphpAgent\Deploy\Adapter;

class Phing {

    public static function Execute($projectFolder,$args=null)
    {
        $success = false;
        if(is_file($projectFolder.'build.xml')){
            $escapeArgs = '';
            if(is_array($args)){
                foreach($args as $propName => $value){
                    $escape = escapeshellarg(' -D'.$propName.'='.$value);
                    $escapeArgs = $escapeArgs . $escape;
                }
            }
            $lastDir = getcwd();
            chdir($projectFolder);
            $result = shell_exec('phing'.$escapeArgs);
            chdir($lastDir);
            $success = true;
        }
        return $success;
    }
} 