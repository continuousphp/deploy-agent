<?php
/**
 * Exception.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Exception.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task\Runner;

/**
 * Exception
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Exception extends \Exception
{
    const BAD_EXIT_EXCEPTION = 0x10;
}
