<?php

namespace CphpAgent\Service;

use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZfcBase\EventManager\EventProvider;
use SebastianBergmann\Exporter\Exception;

use CphpAgent\Deploy\Adapter\Phing;
use CphpAgent\Deploy\Adapter\Tarball;

class DeployManager extends EventProvider implements ServiceManagerAwareInterface, LoggerAwareInterface
{
    /** @var  AgentLogger */
    protected $logger;

    /** @var  ServiceManager */
    protected $serviceManager;

    /** @var  Tarball */
    protected $tarball;

    /**
     * Download, extract and deploy the new build
     *
     * @param $buildId
     * @param $packageUrl
     * @param $project
     * @param $config
     */
    public function deploy($buildId, $packageUrl, $project, $config)
    {
        // @todo: options function or object
//        if (!array_key_exists($project, $config->project))
//            return;

        $projectName = $config->project[$project];

        // @todo: decoupling key mananger
        $keyManager = new ApiKeyManager($buildId);
        $params = array(
            'apikey' => $keyManager->getHash(),
            'project_name' => $project
        );
        $url = $packageUrl . '?' . http_build_query($params);

        $this->getLogger()->info('######## START DEPLOYMENT ########');
        $buildFolder = $config->buildPath . 'build_' . $buildId . '/';
        try {
            $this->setTarball(new Tarball($buildFolder));
            $stream = $this->connect($url);

            if ($this->vadidateHash($keyManager, $stream)) {
                if ($this->delivery($stream)) {
                    $this->pushNewBuild($buildId, $buildFolder, $config->projectPath, $projectName);
                    $this->execute($config->projectPath . $projectName);
                }
            } else {
                $this->getLogger()->error("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            $this->getLogger()->error("An error has occurs during the deployment.");
            $this->getLogger()->error("  Details:" . $e->getMessage());
        }
    }

    /**
     * Connect to the server
     *
     * @param $url
     * @return \Zend\Http\Response
     */
    private function connect($url)
    {
        $this->getLogger()->info('Connect to continuous php server');
        $stream = $this->getTarball()->streamFromUrl($url);
        $this->getLogger()->info('Connected to continuous php server successfully! [done]');

        return $stream;
    }

    /**
     * Donwload and extract the build
     *
     * @param $stream
     * @return bool
     * @throws \SebastianBergmann\Exporter\Exception
     */
    private function delivery($stream)
    {
        $this->getLogger()->info('Downloading tarball');
        $this->getTarball()->createFromResponseStream($stream);

        $this->getLogger()->info('Extraction');
        if (!$extracted = $this->getTarball()->extract()) {
            $this->getLogger()->error('Extaction failed.');
            throw new Exception('Extraction failed.');
        }
        $this->getLogger()->info('Extraction [done]');

        $this->clean();
        return $extracted;
    }

    /**
     * Clean temporary files
     *
     * @return bool
     */
    private function clean()
    {
        $this->getLogger()->info('Delete temporary files');
        $result = $this->getTarball()->cleanTemporaryFile();
        if ($result) $this->getLogger()->info('Deleted temporary files successfully!');

        return $result;
    }

    /**
     * Save and deploy the new build
     *
     * @param $buildId
     * @param $buildFolder
     * @param $workspacePath
     * @param $applicationName
     */
    private function pushNewBuild($buildId, $buildFolder, $workspacePath, $applicationName)
    {
        $this->getLogger()->info('Push new build');
        // save new build in database
        FileSystem::rrmdir($workspacePath . $applicationName);
        FileSystem::xcopy($buildFolder, $workspacePath);
        $this->getLogger()->info('Pushed the new build succesfully! [done]');
    }

    /**
     * Execute phing command
     *
     * @param $destination
     */
    private function execute($destination)
    {
        $this->getLogger()->info("Phing execute.");
        $result = Phing::Execute($destination);
        $this->getLogger()->info("Project deployed successfully!");

        return $result;
    }


    private function vadidateHash($keyManager, $response)
    {
        return true;
        /* Waiting for continuous php validation
        $returnedHash = $response->getHeaders()->get('apiKey');
        return $keyManager->verify($returnedHash);*/
    }

    /**
     * Getter/setter logger
     */
    private function getLogger()
    {
        if (!$this->logger)
            $this->logger = $this->getServiceManager()->get('cphpagent_logger');
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return ServiceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param \CphpAgent\Deploy\Adapter\Tarball $tarball
     * @return \CphpAgent\Deploy\Adapter\Tarball
     */
    public function setTarball($tarball)
    {
        $this->tarball = $tarball;
        return $this->tarball;
    }

    /**
     * @return \CphpAgent\Deploy\Adapter\Tarball
     */
    public function getTarball()
    {
        return $this->tarball;
    }

}