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

namespace CphpAgent\Mapper;

use CphpAgent\FactoryAbstract;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AbstractFactory
 *
 * @package     CphpAgent
 * @subpackage  Mapper
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
     * @param string                  $name
     * @param string                  $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return strpos($requestedName, 'cphp-agent.mapper.') === 0;
    }


}