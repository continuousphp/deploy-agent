<?php
/**
 * Initializer.php
 *
 * @date        09/03/14
 * @file        Initializer.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Mapper;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Initializer
 *
 * @package     CphpAgent
 * @subpackage  Mapper
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
class Initializer implements InitializerInterface
{

    /**
     * Initialize
     *
     * @param mixed                   $instance
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return MapperDoctrineInterface
     * @throws Exception
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof MapperDoctrineInterface) {

            $entityClassName = str_replace('Mapper', 'Entity', get_class($instance));

            if (class_exists($entityClassName)) {
                $instance->setEntityClassName($entityClassName);
            } else {
                throw new Exception('Entity ' . $entityClassName . " class doesn't exist");
            }
        }

        return $instance;
    }

}