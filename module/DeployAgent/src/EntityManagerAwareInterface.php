<?php
/**
 * EntityManagerAwareInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      EntityManagerAwareInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent;

use Doctrine\ORM\EntityManager;

/**
 * EntityManagerAwareInterface
 *
 * @package    Continuous\DeployAgent
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface EntityManagerAwareInterface
{
    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @return EntityManager
     */
    public function getEntityManager();
}
