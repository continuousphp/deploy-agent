<?php

namespace Agent\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZfcBase\EventManager\EventProvider;
use SebastianBergmann\Exporter\Exception;

use Agent\Deploy\Adapter\Phing;
use Agent\Model\Deployment;
use Agent\Service\AgentLogger;
use Agent\Service\ApiKeyManager;
use Agent\ConfigAwareInterface;
use Agent\Deploy\Adapter\Tarball;
use Agent\Service\FileSystem;

class DeployManager extends EventProvider implements ServiceManagerAwareInterface
{

    /** @var  ServiceManager */
    protected $serviceManager;

    public function deploy($buildId, $packageUrl, $config)
    {
        $keyManager = new ApiKeyManager($buildId);
        $url = $packageUrl . '?apikey=' . $keyManager->getHash();
        AgentLogger::initLogger($config->buildPath);
        $buildFolder = $config->buildPath . 'build_' . $buildId . '/';
        try {
            $tarball = new Tarball($buildFolder);
            $stream = $tarball->streamFromUrl($url);
            if ($this->vadidateHash($keyManager, $stream)) {
                $tarball->createFromResponseStream($stream);

                if (!$tarball->extract())
                    throw new Exception('Extraction failed.');
                $tarball->cleanTemporaryFile();
                $projectFolder = $config->projectPath . $config->applicationName;
                $this->pushNewBuild($buildId, $buildFolder, $config->projectPath, $config->applicationName);
                Phing::Execute($projectFolder);
            } else {
                AgentLogger::error("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            AgentLogger::error("An error has occurs during the deployment.");
            AgentLogger::error("  Details:" . $e->getMessage());
        }
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return DeployManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    private function pushNewBuild($buildId, $buildFolder, $workspacePath, $applicationName)
    {
        AgentLogger::info('Push new build');
        $deploymentTable = $this->getDeploymentTable();
        $deploy = new Deployment();
        $deploy->init($buildId, $buildFolder);
        $deploymentTable->saveDeployment($deploy);
        FileSystem::rrmdir($workspacePath . $applicationName);
        FileSystem::xcopy($buildFolder, $workspacePath);
        AgentLogger::info('Push new build [done]');
    }

    private function vadidateHash($keyManager, $response)
    {
        return true;
        /* Waiting for continuous php validation
        $returnedHash = $response->getHeaders()->get('apiKey');
        return $keyManager->verify($returnedHash);*/
    }

    private function getDeploymentTable()
    {
        $sm = $this->getServiceLocator();
        $deploymentTable = $sm->get('Agent\Model\DeploymentTable');
        return $deploymentTable;
    }

}