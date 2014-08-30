<?php


namespace CphpAgent\Log;

use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Stream;

/**
 * Class AgentLogger
 *
 * @package CphpAgent\Log
 */
class AgentLogger extends ZendLogger{

    /**
     * Log message with priority
     *
     * @param int $priority
     * @param mixed $message
     * @param array $extra
     * @return ZendLogger
     */
    final public function log($priority, $message, $extra = array()) {
        return parent::log($priority, $message, $extra);
    }

    /**
     * Log error messages
     *
     * @param $msg
     */
    public function error($msg){
        $this->err($msg);
    }
} 