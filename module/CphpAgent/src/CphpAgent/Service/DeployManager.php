<?php

namespace CphpAgent\Service;

use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
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

class DeployManager extends EventProvider implements ServiceManagerAwareInterface, LoggerAwareInterface
{
    protected $logger;

    /** @var  ServiceManager */
    protected $serviceManager;

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
        // @todo: change in bootstrap? and create sqlite object
        $this->createDatabaseIfNotExists();

        // @todo: options function or object
        if (!array_key_exists($project, $config->project))
            return;

        $projectName = $config->project[$project];
        $keyManager = new ApiKeyManager($buildId);
        $params = array(
            'apikey' => $keyManager->getHash(),
            'project_name' => $project
        );

        $url = $packageUrl . '?' . http_build_query($params);
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
                $this->pushNewBuild($buildId,$buildFolder,$config->projectPath,$projectName);
                Phing::Execute($config->projectPath . $projectName);
            } else {
                AgentLogger::error("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            AgentLogger::error("An error has occurs during the deployment.");
            AgentLogger::error("  Details:" . $e->getMessage());
        }

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

    private function createDatabaseIfNotExists(){
        $dbAdapter = $this->getServiceManager()->get('db');
        $result = $dbAdapter->query("
          SELECT name FROM sqlite_master
              WHERE type='table' AND name='projects'
        ")->execute();

        if($result->current() === false){
            try{
                // @todo : ensure file are the correct perms
                // var_dump(fileperms('./data/db/deploy.sqlite'));

                /**
                 * Problem : Statement could not be executed (HY000 - 14 - unable to open database file)
                 * Solution : http://www.dragonbe.com/2014/01/pdo-sqlite-error-unable-to-open.html
                 *
                 * sudo chgrp www-data data/db
                 * sudo chgrp www-data data/db/deploy.sqlite
                 * sudo chmod g+w data/db/
                 * sudo chmod g+w data/db/deploy.sqlite
                 *
                 */

                $result = $dbAdapter->query("
                    CREATE TABLE projects (
                      id INT(10) NOT NULL,
                      name VARCHAR(20) NOT NULL,
                      PRIMARY KEY (id)
                    )
                ")->execute();

                // insert some project to tests
                $dbAdapter->query("
                  INSERT INTO projects VALUES (1, 'test')
                ")->execute();

            }catch (Exception $e){
                var_dump($e->getMessage());
            }
        }

        $result = $dbAdapter->query("
          SELECT * FROM projects
        ")->execute();

        var_dump($result->current());
    }

    private function getDeploymentTable()
    {
        $sm = $this->getServiceLocator();
        $deploymentTable = $sm->get('CphpAgent\Model\DeploymentTable');
        return $deploymentTable;
    }

    /**
     * Getter/setter logger
     */
    private function getLogger()
    {
        if (!$this->logger)
            $this->logger = $this->getServiceManager()->get('agent_logger_service');
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

}