<?php
/**
 * Application.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Application.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Application;
use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Provider\ProviderInterface;

/**
 * Application
 *
 * @package    Continuous\DeployAgent
 * @subpackage Application
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Application implements ApplicationInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var DestinationInterface
     */
    protected $endPoint;
    
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @param DestinationInterface $endPoint
     * @return $this
     */
    public function setEndPoint(DestinationInterface $endPoint)
    {
        $this->endPoint = $endPoint;
        
        return $this;
    }

    /**
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param ProviderInterface $provider
     * @return $this
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
        
        return $this;
    }
    
    
}