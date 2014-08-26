<?php

namespace Agent\Controller;

use Agent\Model\Deployment;
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
        $buildId = $this->getRequest()->getPost('buildId');
        $url = $this->getRequest()->getPost('packageUrl');
        $keyManager = new ApiKeyManager($buildId);
        $url = $url . '?apikey=' . $keyManager->getHash();
        $logger = $this->createLogger($config->buildPath);
        $buildFolder = $config->buildPath . 'build_'.$buildId.'/';
        try {
            $logger->info('Downloading archive');
            $tarball = new Tarball($buildFolder);
            $stream = $tarball->streamFromUrl($url);
            if ($this->vadidateHash($keyManager, $stream)) {
                $tarball->createFromResponseStream($stream);
                $logger->info('Downloading archive [done]');

                $logger->info('Extraction');
                if ($tarball->extract())
                    $logger->info('Extraction [done]'); /**/
                else
                    throw new Exception('Extraction failed.');

                $logger->info('Delete temporary files');
                $tarball->cleanTemporaryFile();
                $logger->info('Delete temporary files [done]');

                $logger->info('Push new build');
                $deploymentTable = $this->getDeploymentTable();
                $deploy = new Deployment();
                $deploy->init($buildId,$buildFolder);
                $deploymentTable->saveDeployment($deploy);
                FileSystem::rrmdir($config->projectPath . $config->applicationName);
                FileSystem::xcopy($buildFolder,$config->projectPath);
                $logger->info('Push new build [done]');
            } else {
                $logger->err("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            $logger->err("An error has occurs during the deployment.");
            $logger->err("Details:" . $e->getMessage());
        }
        return new ViewModel(array());
    }

    private function vadidateHash($keyManager, $response)
    {
        return true;
        /* Waiting for continuous php validation
        $returnedHash = $response->getHeaders()->get('apiKey');
        return $keyManager->verify($returnedHash);*/
    }

    private function createLogger($filePath)
    {
        /** @todo: create logger object */
        $logger = new Logger();
        FileSystem::mkdirp($filePath, 0777, true);
        $writer = new Stream($filePath . 'deployment.log');
        $logger->addWriter($writer);
        $logger->info('######## START DEPLOYMENT ########');
        return $logger;
    }

    private function getDeploymentTable()
    {
            $sm = $this->getServiceLocator();
            $deploymentTable = $sm->get('Agent\Model\DeploymentTable');
        return $deploymentTable;
    }
}
