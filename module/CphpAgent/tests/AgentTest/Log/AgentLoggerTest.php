<?php

namespace CphpAgentTest\Log;

use CphpAgent\Log\AgentLogger;
use Zend\Log\Writer\Mock;

/**
 * Class AgentLoggerTest
 * @package CphpAgentTest\Log
 */
class AgentLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AgentLogger */
    private $logger;

    /**
     * Setup
     */
    protected function setUp(){
        $writer = new Mock();
        $this->logger = new AgentLogger();
        $this->logger->addWriter($writer);
    }

    /**
     * Test default log
     */
    public function testLog(){
        $message = 'unit test testLog';
        $this->assertInstanceOf('CphpAgent\Log\AgentLogger', $this->logger->log(AgentLogger::DEBUG, $message));
    }

} 