<?php
/**
 * ApplicationInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ApplicationInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Application;

use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Provider\ProviderInterface;

/**
 * ApplicationInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Application
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface ApplicationInterface
{
    /**
     * @param ProviderInterface $provider
     * @return $this
     */
    public function setProvider(ProviderInterface $provider);

    /**
     * @return ProviderInterface
     */
    public function getProvider();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return DestinationInterface
     */
    public function getEndPoint();
}
