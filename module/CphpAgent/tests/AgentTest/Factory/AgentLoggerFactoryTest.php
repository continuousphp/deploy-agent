<?php

namespace CphpAgentTest\Log;

use CphpAgent\Factory\AgentLoggerFactory;
use CphpAgentTest\Bootstrap;

/**
 * Class AgentLoggerFactoryTest
 *
 * @package CphpAgentTest\Log
 */
class AgentLoggerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AgentLoggerFactory */
    private $factory;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->factory = new AgentLoggerFactory();
    }

    /**
     * Test writer
     */
    public function testWriter()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->factory->createService($serviceManager);
        foreach ($this->factory->getLogger()->getWriters() as $writer) {
            $this->assertInstanceOf('Zend\Log\Writer\Stream', $writer);
        }
    }

} 