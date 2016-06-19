<?php
/**
 * DeployEvent.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      DeployEvent.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Event;

use Continuous\DeployAgent\Workspace\Workspace;
use Zend\EventManager\Event;

/**
 * DeployEvent
 *
 * @package    Continuous\DeployAgent
 * @subpackage Event
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class DeployEvent extends Event
{
    const EVENT_FETCH_PRE     = 'source.fetch.pre';
    const EVENT_FETCH_POST    = 'source.fetch.post';
    const EVENT_RECEIVE       = 'destination.receive';
    const EVENT_RECEIVE_PRE   = 'destination.receive.pre';
    const EVENT_RECEIVE_POST  = 'destination.receive.post';
    const EVENT_RECEIVE_ERROR = 'destination.receive.error';

    /**
     * @var Workspace
     */
    protected $workspace;

    /**
     * @var \SplFileInfo
     */
    protected $source;

    /**
     * @var \SplFileInfo
     */
    protected $destination;

    /**
     * @var \Exception
     */
    protected $error;

    /**
     * @return Workspace
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @param Workspace $workspace
     * @return self
     */
    public function setWorkspace(Workspace $workspace)
    {
        $this->workspace = $workspace;

        return $this;
    }

    /**
     * @return \SplFileInfo
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \SplFileInfo $source
     * @return self
     */
    public function setSource(\SplFileInfo $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return \SplFileInfo
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param \SplFileInfo $destination
     * @return self
     */
    public function setDestination(\SplFileInfo $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return \Exception
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param \Exception $error
     * @return self
     */
    public function setError(\Exception $error = null)
    {
        $this->error = $error;

        return $this;
    }
}
