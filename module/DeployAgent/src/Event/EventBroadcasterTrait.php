<?php
/**
 * EventBroadcasterTrait.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      EventBroadcasterTrait.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerInterface;

/**
 * EventBroadcasterTrait
 *
 * @package    Continuous\DeployAgent
 * @subpackage Event
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
trait EventBroadcasterTrait
{
    use EventManagerAwareTrait;

    /**
     * // @todo: detach listeners strategy ?
     *
     * @param EventManagerInterface $events
     * @param string                $prefixWith
     * @param bool                  $replaceTarget
     */
    public function broadcastEvents(EventManagerInterface $events, $prefixWith = '', $replaceTarget = true)
    {
        $events->attach('*', function(EventInterface $event) use($prefixWith, $replaceTarget) {
            if (true === $replaceTarget) {
                $event->setTarget($this);
            }
            if ($prefixWith) {
                $event->setName($prefixWith . '.' . $event->getName());
            }
            $this->getEventManager()->trigger($event);
        });
    }
}