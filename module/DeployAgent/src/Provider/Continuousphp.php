<?php
/**
 * Continuousphp.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Continuousphp.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Provider;
use Continuous\Sdk\Service;

/**
 * Continuousphp
 *
 * @package    Continuous\DeployAgent
 * @subpackage Provider
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Continuousphp implements ProviderInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \Continuous\Sdk\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $repositoryProvider;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        
        return $this;
    }

    /**
     * @return \Continuous\Sdk\Client
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->client = Service::factory(['token' => $this->token]);
        }
        
        return $this->client;
    }

    /**
     * return array
     */
    public function getRevisions()
    {
        return $this->getClient()
            ->getBuilds([
                'provider' => $this->getRepositoryProvider(),
                'repository' => $this->getRepository(),
                'ref' => $this->getReference(),
                'state' => ['complete'],
                'result' => ['success', 'warning']
            ]);
    }

    /**
     * return array
     */
    public function getProjects()
    {
        return $this->getClient()
            ->getProjects()['_embedded']['projects'];
    }

    /**
     * @param array $project
     * @return $this
     */
    public function setProject(array $project)
    {
        $this->setRepositoryProvider($project['_embedded']['provider']['uniqueIdentifier']);
        $this->setRepository($project['url']);
        
        return $this;
    }

    /**
     * @param string $revision
     * @return Http
     */
    public function getSource($revision)
    {
        
    }

    /**
     * @return string
     */
    public function getRepositoryProvider()
    {
        return $this->repositoryProvider;
    }

    /**
     * @param string $repositoryProvider
     * @return $this
     */
    public function setRepositoryProvider($repositoryProvider)
    {
        $this->repositoryProvider = $repositoryProvider;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
        
        return $this;
    }
    
    public function getReferences()
    {
        $references = [];
        
        $pipelines = $this->getClient()
            ->getPipelines([
                'provider' => $this->getRepositoryProvider(),
                'repository' => $this->getRepository()
            ]);
        
        foreach ($pipelines['_embedded']['settings'] as $pipeline) {
            $references[] = $pipeline['settingId'];
        }
        
        return $references;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }
}