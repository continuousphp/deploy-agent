<?php
/**
 * Command.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Command.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task\Runner;

/**
 * Command
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Command implements TaskRunnerInterface
{
    protected $command;

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return Command
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }
    
    public function run()
    {
        passthru($this->getCommand(), $return);
        if ($return) {
            throw new Exception('The command exit with code ' . $return, Exception::BAD_EXIT_EXCEPTION);
        }
    }

}