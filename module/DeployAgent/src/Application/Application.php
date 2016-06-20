<?php
/**
 * Application.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Application.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Application;

use Doctrine\ORM\Mapping as ORM;
use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Provider\ProviderInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Application
 *
 * @package    Continuous\DeployAgent
 * @subpackage Application
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 *
 * @ORM\Entity
 */
class Application implements ApplicationInterface
{
    use EventManagerAwareTrait;
    
    const EVENT_INSTALL = 'Install';
    const EVENT_AFTER_INSTALL = 'AfterInstall';
    const EVENT_BEFORE_ACTIVATE = 'BeforeActivate';
    const EVENT_ACTIVATE = 'Activate';
    const EVENT_AFTER_ACTIVATE = 'AfterActivate';
    
    /**
     * The application name
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="Continuous\DeployAgent\Provider\AbstractProvider",
     *               mappedBy="application",
     *               cascade={"persist"},
     *               orphanRemoval=true)
     *
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $path;

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
        $provider->setApplication($this);
        $this->provider = $provider;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndPoint()
    {
    }
}
