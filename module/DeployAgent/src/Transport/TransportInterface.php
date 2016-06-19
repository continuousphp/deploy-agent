<?php
/**
 * TransportInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TransportInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Transport;

use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Source\SourceInterface;

/**
 * TransportInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Transport
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface TransportInterface
{
    /**
     * @param SourceInterface $source
     * @return self
     */
    public function from(SourceInterface $source);

    /**
     * @param DestinationInterface $destination
     * @return self
     */
    public function to(DestinationInterface $destination);

    public function move();
}
