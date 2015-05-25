<?php

namespace CphpAgent\Controller;

use CphpAgent\Deploy\Adapter\Tarball;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use CphpAgent\Config\ConfigAwareInterface;
use Zend\View\Model\ViewModel;
use Zend\Config\Config;
use Zend\Http\Response;
use CphpAgent\Service\DeployManager as DeployManagerService;

class IndexController extends AbstractActionController implements ConfigAwareInterface, ServiceLocatorAwareInterface
{
    /** @var  Config */
    protected $config;

    /** @var  DeployManagerService */
    private $deployManagerService;


    public function indexAction()
    {
        if (class_exists('\ZF\Apigility\Admin\Module', false)) {
            return $this->redirect()->toRoute('zf-apigility/ui');
        }
        return new ViewModel();
        set_time_limit(0);
        ini_set('max_execution_time', 300);

        $config = new Config($this->getConfig());
        $build = $this->params()->fromPost('build_id');
        $url = $this->params()->fromPost('package_url');
        $project = $this->params()->fromQuery('project_name');

        if (!empty($config) && !empty($url) && !empty($build) && !empty($project)) {
            $service = $this->getDeployManagerService();
            $service->deploy($build, $url, $project, $config);
            return new ViewModel(array());
        }else{
            return $this->redirect()->toRoute('zfcadmin/login');
        }
    }


    /**
     * Getters/setters for DI
     */
    public function getDeployManagerService()
    {
        if (!$this->deployManagerService) {
            $this->deployManagerService = $this->getServiceLocator()->get('cphp-agent.service.deploy-manager');
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
