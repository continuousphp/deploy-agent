<?php
/**
 * Agent.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Agent.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Agent;

use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Event\EventBroadcasterTrait;
use Continuous\DeployAgent\Resource\ResourceInterface;
use Continuous\DeployAgent\Source\SourceInterface;
use Continuous\DeployAgent\Transport\Transport;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Agent
 *
 * @package    Continuous\DeployAgent
 * @subpackage Agent
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Agent implements EventManagerAwareInterface
{
    use EventBroadcasterTrait;

    /**
    protected $transport;

    /**
     * @var DestinationInterface
     */
    protected $destination;

    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * @var ResourceInterface
     */
    protected $resource;

    public function __construct()
    {
        $this->transport = new Transport();
        $this->broadcastEvents($this->transport->getEventManager());
    }

    /**
     * @param SourceInterface $source
     *
     * @return $this
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return $this
     */
    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @param DestinationInterface $destination
     * @return $this
     */
    public function setDestination(DestinationInterface $destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function deploy()
    {
        // Pull
        $this->transport
            ->from($this->source)
            ->to($this->resource)
            ->move()
        ;

        // Push
        $this->transport
            ->from($this->resource)
            ->to($this->destination)
            ->move()
        ;
    }
}