<?php
/**
 * MapperDoctrineInterface.php
 *
 * @date        09/03/14
 * @file        MapperDoctrineInterface.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Mapper;

use CphpAgent\Entity\EntityAbstract;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * MapperDoctrineInterface
 *
 * @package     CphpAgent
 * @subpackage  Mapper
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
interface MapperDoctrineInterface
{
    /**
     * Get entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * Set entity Manager
     *
     * @param EntityManager $entityManager
     *
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * Set entityRepository
     *
     * @param EntityRepository $entityRepository
     */
    public function setEntityRepository(EntityRepository $entityRepository);

    /**
     * Get entityRepository
     *
     * @return EntityRepository
     */
    public function getEntityRepository();

    /**
     * set entity class name
     *
     * @param $entityClassName
     *
     * @return $this
     */
    public function setEntityClassName($entityClassName);

    /**
     * get entity class Name
     *
     * @return string
     */
    public function getEntityClassName();

    /**
     * Persists the passed entity
     *
     * @param EntityAbstract $entity
     *
     * @return bool
     */
    public function store(EntityAbstract $entity);
}