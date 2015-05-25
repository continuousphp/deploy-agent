<?php
/**
 * DestinationInterface.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      DestinationInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Destination;

use Continuous\DeployAgent\Workspace\Workspace;

/**
 * DestinationInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Destination
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface DestinationInterface
{
    /**
     * @param Workspace $build
     * @return mixed
     */
    public function receive(Workspace $build);
}