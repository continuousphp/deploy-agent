<?php
/**
 * FileSystemException.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      FileSystemException.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Exception;

/**
 * FileSystemException
 *
 * @package    Continuous\DeployAgent
 * @subpackage Exception
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class FileSystemException extends \Exception
{
    const CREATION_ERROR  = 0x10;
    const COPY_ERROR      = 0x11;
    const NOT_FOUND_ERROR = 0x12;
    const TYPE_ERROR      = 0x13;
}