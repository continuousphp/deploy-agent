<?php

namespace Agent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Config\Config;
use Zend\Http\Response;

use Agent\Service\DeployManager as DeployManagerService;


class AgentController extends AbstractActionController implements ConfigAwareInterface
{
    /** @var  Config */
    protected $config;

    /** @var  DeployManagerService */
    private $deployManagerService;

    public function indexAction()
    {
        $config = new Config($this->getConfig());
        $buildId = $this->getRequest()->getPost('build_id');
        $url = $this->getRequest()->getPost('package_url');
        if (empty($url)) return; // return 200

        // @todo Remove default testing
        $projectRepo = $this->getRequest()->getPost('repository_name','testing');
        $projectName = $config->project[$projectRepo];
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
                $this->pushNewBuild($buildId,$buildFolder,$config->projectPath,$projectName);
                Phing::Execute($config->projectPath . $projectName);
            } else {
                AgentLogger::error("Invalid api key. Deployment aborted");
            }
        } catch (Exception $e) {
            AgentLogger::error("An error has occurs during the deployment.");
            AgentLogger::error("  Details:" . $e->getMessage());
        if (!empty($config) && !empty($url) && !empty($buildId)) {
            $service = $this->getDeployManagerService();
            $service->deploy($buildId, $url, $config);
        }
        return new ViewModel(array());
    }

    public function adminAction()
    {
        return new ViewModel(array(
            'deployments' => $this->getDeploymentTable()->fetchAll(),
        ));
    }

    /**
     * Getters/setters for DI
     */
    public function getDeployManagerService()
    {
        if (!$this->deployManagerService) {
            $this->deployManagerService = $this->getServiceLocator()->get('agent_deploymanager_service');
        }
        return $this->deployManagerService;
    }

    public function setDeployManageService(DeployManager $deployManagerService)
    {
        $this->deployManagerService = $deployManagerService;
        return $this;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

}
