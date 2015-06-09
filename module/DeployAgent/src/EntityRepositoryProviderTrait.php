<?php
/**
 * EntityRepositoryProviderTrait.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      EntityRepositoryProviderTrait.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * EntityRepositoryProviderTrait
 *
 * @package    Continuous\DeployAgent
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
trait EntityRepositoryProviderTrait
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $entityRepository;

    /**
     * @param EntityManager $entityManager
     * @return $this
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
     * @return EntityRepository
     */
    public function getEntityRepository()
    {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->getEntityManager()->getRepository(static::ENTITY_CLASSNAME);
        }
        
        return $this->entityRepository;
    }
}