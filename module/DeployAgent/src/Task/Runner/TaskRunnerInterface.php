<?php
/**
 * TaskRunnerInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TaskRunnerInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task\Runner;

use Zend\EventManager\Event;

/**
 * TaskRunnerInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface TaskRunnerInterface
{
    public function run(Event $e);
}
