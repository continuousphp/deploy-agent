<?php
/**
 * ResourceInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ResourceInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource;

use Continuous\DeployAgent\Destination\DestinationInterface;
use Continuous\DeployAgent\Source\SourceInterface;

/**
 * ResourceInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface ResourceInterface extends SourceInterface, DestinationInterface
{

}
