<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 20/06/16
 * Time: 09:33
 */

namespace Continuous\DeployAgent\Task\Runner;


class Exception extends \Exception
{
    const BAD_EXIT_EXCEPTION = 0x10;
}