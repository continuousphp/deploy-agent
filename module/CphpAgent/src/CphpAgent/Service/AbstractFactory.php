<?php
/**
 * AbstractFactory.php
 *
 * @date        09/03/14
 * @file        AbstractFactory.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Service;

use CphpAgent\FactoryAbstract;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * AbstractFactory
 *
 * @package     CphpAgent
 * @subpackage  Service
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
class AbstractFactory extends FactoryAbstract implements AbstractFactoryInterface
{
    /**
     * Add current namespace
     */
    public function __construct()
    {
        $this->addNamespace(__NAMESPACE__);
    }

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return strpos($requestedName, 'cphp-agent.service.') === 0;
    }
}