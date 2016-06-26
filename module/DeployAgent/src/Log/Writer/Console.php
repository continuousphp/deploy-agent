<?php
/**
 * Console.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Console.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Log\Writer;

use Zend\Console\ColorInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\AbstractWriter;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapter;

/**
 * Console
 *
 * @package    Continuous\DeployAgent
 * @subpackage Log
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Console extends AbstractWriter
{
    /** @var ConsoleAdapter */
    protected $console;
    
    /** @var array */
    protected $colors = [
        Logger::DEBUG => ColorInterface::GRAY,
        Logger::INFO => ColorInterface::LIGHT_CYAN,
        Logger::NOTICE => ColorInterface::LIGHT_BLUE,
        Logger::WARN => ColorInterface::LIGHT_YELLOW,
        Logger::ERR => ColorInterface::LIGHT_MAGENTA,
        Logger::CRIT => ColorInterface::LIGHT_RED,
        Logger::ALERT => ColorInterface::MAGENTA,
        Logger::EMERG => ColorInterface::RED
    ];

    /**
     * @return ConsoleAdapter
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * @param ConsoleAdapter $console
     * @return Console
     */
    public function setConsole($console)
    {
        $this->console = $console;
        return $this;
    }
    
    protected function doWrite(array $event)
    {
        if ($this->console) {
            $this->console->writeLine($event['message'], $this->getColor($event['priority']));
        }
    }
    
    protected function getColor($priority)
    {
        return $this->colors[$priority];
    }
}
