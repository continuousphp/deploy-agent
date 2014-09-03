<?php

namespace CphpAgent\Factory;

use SebastianBergmann\Exporter\Exception;
use Zend\Log\Filter\Priority;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use CphpAgent\Log\AgentLogger as AgentLogger;
use CphpAgent\Service\FileSystem;

/**
 * Class AgentLoggerFactory
 *
 * @package CphpAgent\Factory
 */
class AgentLoggerFactory implements FactoryInterface
{
    /** @var AgentLogger */
    private $logger;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AgentLogger|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['deployAgent']['logger'];
        try {
            $this->setLogger(new AgentLogger());
            $this->configureWriters($config);
        } catch (Exception $e) {
            throw new Exception('Problem occurs when creating an agent logger');
        }
        return $this->getLogger();
    }

    /**
     * Configure writers from config
     *
     * @param array $config
     * @return int
     */
    protected function configureWriters(array $config)
    {
        if (!empty($config['writers'])) {
            $writers = 0;
            foreach ($config['writers'] as $writer) {
                if ($writer['enabled']) {
                    $this->addWriter($writer);
                    $writers++;
                }
            }
            return $writers;
        }
        return false;
    }

    /**
     * Add writer into logger
     *
     * @param array $writer
     * @return mixed
     */
    protected function addWriter(array $writer)
    {
        $adapter = $writer['adapter'];
        $path = $writer['options']['output'];

        FileSystem::mkdirp($path, 0777, true);

        if (!empty($writer['options']['file']))
            $path .= $writer['options']['file'];

        $stream = new $adapter($path);
        $this->getLogger()->addWriter($stream);
        $stream->addFilter(new Priority($writer['filter']));

        return $adapter;
    }

    /**
     * @param \CphpAgent\Log\AgentLogger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return \CphpAgent\Log\AgentLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }


}