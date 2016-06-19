<?php
/**
 * Transport.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Transport.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */
namespace Continuous\DeployAgent\Transport;

use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Event\EventBroadcasterTrait;
use Continuous\DeployAgent\Source\SourceInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Transport
 *
 * @package    Continuous\DeployAgent
 * @subpackage Transport
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Transport implements TransportInterface, EventManagerAwareInterface
{
    use EventBroadcasterTrait;

    /**
     * @var SourceInterface
     */
    protected $source;

    /**
     * @var DestinationInterface
     */
    protected $destination;

    /**
     * @param SourceInterface $origin
     * @return self
     */
    public function from(SourceInterface $origin)
    {
        if ($origin instanceof EventManagerAwareInterface) {
            $this->broadcastEvents($origin->getEventManager());
        }
        $this->source = $origin;

        return $this;
    }

    /**
     * @param DestinationInterface $destination
     * @return self
     */
    public function to(DestinationInterface $destination)
    {
        if ($destination instanceof EventManagerAwareInterface) {
            $this->broadcastEvents($destination->getEventManager());
        }
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return mixed (?)
     */
    public function move()
    {
        $events = $this->getEventManager();
        $events->trigger('fetch.pre');
        $data = $this->source->fetch();
        $events->trigger('fetch.post');

        $events->trigger('receive.pre');
        $result = $this->destination->receive($data);
        $events->trigger('receive.post');

        return $result;
    }
}
