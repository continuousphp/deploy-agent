<?php
/**
 * EntityRepositoryProviderInterface.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      EntityRepositoryProviderInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent;

use Doctrine\ORM\EntityRepository;

/**
 * EntityRepositoryProviderInterface
 *
 * @package    Continuous\DeployAgent
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface EntityRepositoryProviderInterface
{
    /**
     * @return EntityRepository
     */
    public function getEntityRepository();
}
