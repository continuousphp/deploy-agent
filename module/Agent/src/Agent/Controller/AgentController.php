<?php

namespace Agent\Controller;

use Agent\Deploy\Adapter\Phing;
use Agent\Model\Deployment;
use Agent\Service\AgentLogger;
use Agent\Service\ApiKeyManager;
use SebastianBergmann\Exporter\Exception;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\Uri;
use Zend\View\Model\ViewModel;
use Zend\Config\Config;
use Zend\Http\Response;

use Agent\ConfigAwareInterface;
use Agent\Deploy\Adapter\Tarball;
use Agent\Service\FileSystem;


class AgentController extends AbstractActionController implements ConfigAwareInterface
{
    protected $config;
    private $deploymentTable;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function indexAction()
    {
        $config = new Config($this->getConfig());
        $buildId = $this->getRequest()->getPost('build_id');
        $url = $this->getRequest()->getPost('package_url');
        $keyManager = new ApiKeyManager($buildId);
        $url = $url . '?apikey=' . $keyManager->getHash();
        AgentLogger::initLogger($config->buildPath);
        $buildFolder = $config->buildPath . 'build_'.$buildId.'/';
        try {
            $tarball = new Tarball($buildFolder);
            $stream = $tarball->streamFromUrl($url);
            if ($this->vadidateHash($keyManager, $stream)) {
                $tarball->createFromResponseStream($stream);

                if (! $tarball->extract())
                    throw new Exception('Extraction failed.');
                $tarball->cleanTemporaryFile();
                $projectFolder =$config->projectPath . $config->applicationName;
                $this->pushNewBuild($buildId,$buildFolder,$projectFolder);
                Phing::Execute($projectFolder);
            } else {
                AgentLogger::error("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            AgentLogger::error("An error has occurs during the deployment.");
            AgentLogger::error("  Details:" . $e->getMessage());
        }
        return new ViewModel(array());
    }

    private function pushNewBuild($buildId,$buildFolder,$projectFolder)
    {
        AgentLogger::info('Push new build');
        $deploymentTable = $this->getDeploymentTable();
        $deploy = new Deployment();
        $deploy->init($buildId,$buildFolder);
        $deploymentTable->saveDeployment($deploy);
        FileSystem::rrmdir($projectFolder);
        FileSystem::xcopy($buildFolder,$config->projectPath);
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
