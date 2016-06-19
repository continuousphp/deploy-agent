<?php
/**
 * SourceInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      SourceInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Source;

use Continuous\DeployAgent\Workspace\Workspace;

/**
 * SourceInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Source
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface SourceInterface
{
    /**
     * @return Workspace
     */
    public function fetch();
}
