<?php
/**
 * ApplicationManager.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ApplicationManager.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Application;

use Continuous\DeployAgent\EntityManagerAwareInterface;
use Continuous\DeployAgent\EntityRepositoryProviderInterface;
use Continuous\DeployAgent\EntityRepositoryProviderTrait;
use Doctrine\Common\Collections\Collection;

/**
 * ApplicationManager
 *
 * @package    Continuous\DeployAgent
 * @subpackage Application
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ApplicationManager implements EntityManagerAwareInterface, EntityRepositoryProviderInterface
{
    use EntityRepositoryProviderTrait;
    
    const ENTITY_CLASSNAME = 'Continuous\\DeployAgent\\Application\\Application';

    /**
     * @param $name
     * @return Application
     */
    public function get($name)
    {
        return $this->getEntityRepository()
            ->find($name);
    }

    /**
     * @param $name
     * @return Application
     */
    public function find($provider, $repositoryProvider, $repository, $pipeline)
    {
        $dataRepository = $this->getEntityManager()
            ->getRepository('Continuous\DeployAgent\Provider\\' . ucfirst($provider));
        
        $providers = $dataRepository->findBy(
            [
                'repositoryProvider' => $repositoryProvider,
                'repository' => $repository,
                'reference' => $pipeline
            ],
            null,
            1
        );
        
        if (!empty($providers)) {
            return $providers[0]->getApplication();
        }
    }

    /**
     * @return Collection
     */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
     * @param Application $application
     */
    public function persist(Application $application)
    {
        $this->getEntityManager()
            ->persist($application);
        $this->getEntityManager()
            ->flush();
    }
}
