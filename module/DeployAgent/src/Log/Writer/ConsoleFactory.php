<?php
/**
 * ConsoleFactory.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ConsoleFactory.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Log\Writer;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ConsoleFactory
 *
 * @package    Continuous\DeployAgent
 * @subpackage Log
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ConsoleFactory implements FactoryInterface
{
    protected $options = [];
    
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $pluginManager
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $writer = new Console($this->options);
        
        $console = $pluginManager->getServiceLocator()->get('Console');
        if ($console instanceof \Zend\Console\Adapter\AdapterInterface) {
            $writer->setConsole($console);
        }

        return $writer;
    }
}
