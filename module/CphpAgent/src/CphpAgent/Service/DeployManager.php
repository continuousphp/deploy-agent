<?php

namespace CphpAgent\Service;

use CphpAgent\Log\AgentLogger;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcBase\EventManager\EventProvider;
use SebastianBergmann\Exporter\Exception;

use CphpAgent\Deploy\Adapter\Tarball;
use CphpAgent\Api\KeyManager;

class DeployManager extends EventProvider implements ServiceLocatorAwareInterface, LoggerAwareInterface
{
    use ServiceLocatorAwareTrait, LoggerAwareTrait;
    /** @var  AgentLogger */
    protected $logger;


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
        if (!array_key_exists($project, $config->projects)){
            $this->getLogger()->info('### THIS PROJECT DOESN\'T EXIST IN CONFIG FILE! ###');
            return;
        }

        $projectConfig = $config->projects->{$project};
        $projectFolder = $projectConfig->folder;

        $keyManager = new KeyManager();
        $keyManager->generate($buildId);
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
                if ($this->retrieve($stream)) {
                    $this->pushNewBuild($buildId, $buildFolder, $config->projectPath, $projectConfig);
                    $this->execute($config->projectPath . $projectFolder);
                }
            } else {
                $this->getLogger()->error('Invalid api key. Deployment aborted');
            }
        } catch (Exception $e) {
            $this->getLogger()->error('An error has occurs during the deployment.');
            $this->getLogger()->error('  Details:' . $e->getMessage());
            $this->getLogger()->error('  Details:' . $e->getTraceAsString());
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
        $this->getLogger()->info('Connect to continuous php server...');
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
    private function retrieve($stream)
    {
        $this->getLogger()->info('Downloading tarball...');
        $this->getTarball()->createFromResponseStream($stream);
        $this->getLogger()->info('Downloaded [done]');

        $this->getLogger()->info('Extracting...');
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
        $this->getLogger()->info('Deleting temporary files');
        $result = $this->getTarball()->cleanTemporaryFile();
        if ($result)
            $this->getLogger()->info('Deleted temporary files successfully! [done]');
        else
            $this->getLogger()->error('Deletion failed!');

        return $result;
    }

    /**
     * Save and deploy the new build
     *
     * @param $buildId
     * @param $buildFolder
     * @param $wwwPath
     * @param $projectConfig
     */
    private function pushNewBuild($buildId, $buildFolder, $wwwPath, $projectConfig)
    {
        $this->getLogger()->info('Pushing new build...');

        // save build in database
        $this->storeBuild($buildId);

        // create link
        $currentPath = $wwwPath . $projectConfig->folder;
        if (filetype($currentPath) == 'link') unlink($currentPath);
        FileSystem::link($buildFolder, $currentPath);

        foreach($projectConfig->permanentResources as $resource => $link){
            FileSystem::mkdirp($resource);
            if (FileSystem::link($resource, $currentPath . $link))
                $this->getLogger()->info('Permanent resource ' . $link . ' created.');
            else
                $this->getLogger()->error('Creation of permanent resource ' . $link . ' failed.');

        }

        $this->getLogger()->info('Pushed the new build succesfully! [done]');
    }

    /**
     * Store build in database
     *
     * @param $buildId
     */
    private function storeBuild($buildId){
        $buildMapper = $this->getServiceLocator()->get('cphp-agent.mapper.build');
        $data = [
            'name' => $buildId,
            'date' => new \DateTime('now'),
        ];
        $build = new \CphpAgent\Entity\Build();
        $build->exchangeArray($data);
        $buildMapper->store($build);
        $buildMapper->flush();
    }

    /**
     * Execute Phing command
     *
     * @param $destination
     * @return bool
     */
    private function execute($destination)
    {
        $buildResult = false;
        $this->getLogger()->info('Executing Phing ...');
        $buildFile = $destination . 'build.xml';
        if(is_file($buildFile)){
            $options = array('buildFile' => $buildFile);
            $buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('show-defaults dist', $options);
            $this->getLogger()->info(implode($buildResult));
        }else{
            $this->getLogger()->error('No build.xml phing file find in root directory!');
        }
        $this->getLogger()->info('Project deployed successfully! [done]');

        return $buildResult;
    }


    private function vadidateHash($keyManager, $response)
    {
        return true;
        /* Waiting for continuous php validation
        $returnedHash = $response->getHeaders()->get('apiKey');
        return $keyManager->verify($returnedHash);*/
    }

    /**
     * Set Agent Logger
     *
     * @return array|AgentLogger|object
     */
    public function getLogger()
    {
        if (!$this->logger)
            $this->logger = $this->getServiceLocator()->get('cphp-agent.logger');
        return $this->logger;
    }

    /**
     * Get Agent Logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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