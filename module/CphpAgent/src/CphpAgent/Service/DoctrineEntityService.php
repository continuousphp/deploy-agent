<?php
namespace CphpAgent\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DoctrineEntityService implements
    ServiceManagerAwareInterface
{
    /** @var **/
    protected $serviceManager;
    protected $eventManager;
    protected $entityManager;
    protected $entityRepository;


    /**
     * Returns all Entities
     *
     * @return EntityRepository
     */
    public function findAll()
    {
        $entities = $this->getEntityRepository()->findAll();
        return $entities;
    }

    public function find($id) {
        return $this->getEntityRepository()->find($id);
    }

    public function findByQuery(\Closure $query)
    {
        $queryBuilder = $this->getEntityRepository()->createQueryBuilder('entity');
        $currentQuery = call_user_func($query, $queryBuilder);
        return $currentQuery->getQuery()->getResult();
    }

    /**
     * Persists and Entity into the Repository
     *
     * @param Entity $entity
     * @return Entity
     */
    public function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $entityRepository
     * @return \CphpAgent\Service\DoctrineEntityService
     */
    public function setEntityRepository(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
        return $this;
    }

    /**
     * @param EntityManager $entityManager
     * @return \CphpAgent\Service\DoctrineEntityService
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return \CphpAgent\Service\DoctrineEntityService
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}