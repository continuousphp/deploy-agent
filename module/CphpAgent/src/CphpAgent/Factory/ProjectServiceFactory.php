<?php

namespace CphpAgent\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use CphpAgent\Service\ProjectService;

class ProjectServiceFactory implements FactoryInterface
{
    /**
     * Create the project service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProjectService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new ProjectService();
        $service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        return $service;
    }
}