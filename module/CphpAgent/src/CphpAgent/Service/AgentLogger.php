<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 27/08/14
 * Time: 15:58
 */

namespace CphpAgent\Service;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class AgentLogger {

    private static $logger = null;

    public function __construct() {
    }

    public static function initLogger($filePath) {
        if(is_null(self::$logger)) {
            self::$logger =  new Logger();
            FileSystem::mkdirp($filePath, 0777, true);
            $writer = new Stream($filePath . 'deployment.log');
            self::$logger->addWriter($writer);
            self::$logger->info('######## START DEPLOYMENT ########');
        }
    }

    public static function info($msg){
        if(! is_null(self::$logger))
            self::$logger->info($msg);
    }

    public static function error($msg){
        if(! is_null(self::$logger))
            self::$logger->err($msg);
    }

} 