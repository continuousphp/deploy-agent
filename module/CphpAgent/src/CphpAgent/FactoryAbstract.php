<?php
/**
 * AbstractFactoryAbstract.php
 *
 * @date        09/03/14
 * @file        FactoryAbstract.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent;

use Zend\Filter\StaticFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class        FactoryAbstract
 *
 * @package     CphpAgent
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
abstract class FactoryAbstract implements NamespaceManagerAwareInterface
{
    use NamespaceManagerAwareTrait;

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @param string $requestedName
     *
     * @throws ServiceNotFoundException
     * @return object
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $requestedName     = explode('.', $requestedName);
        $nameSpaceExploded = explode('/', end($requestedName));

        $className = '';
        foreach ($nameSpaceExploded as $nameSpaces) {
            $className .= '\\' . \ucfirst(StaticFilter::execute($nameSpaces, 'Word\DashToCamelCase'));
        }
        
        foreach ($this->getNamespaces() as $namespace) {
            $classNameWithNameSpace = '\\' . $namespace . $className;
            if (class_exists($classNameWithNameSpace)) {
                return new $classNameWithNameSpace;
            }
        }

        throw new ServiceNotFoundException('No service available for class name ' . (string) $className);
    }
}