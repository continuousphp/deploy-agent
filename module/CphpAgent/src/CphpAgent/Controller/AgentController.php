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

class AgentController extends AbstractActionController implements ConfigAwareInterface, ServiceLocatorAwareInterface
{
    /** @var  Config */
    protected $config;

    /** @var  DeployManagerService */
    private $deployManagerService;


    public function indexAction()
    {
//        $service = new Tarball('/tmp/www/');
        //$service->downloadArchive('https://continuousphp-us-west-2.s3-us-west-2.amazonaws.com/17/refs/heads/dev/28eafa36-a767-48d9-a897-057a7ea85b8a/deploy-package.tar.gz?x-amz-security-token=AQoDYXdzENn%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEa0ANiX8bY4tN8LTdks3Q2pStc9XcNosJsl2H4xeTDTjJVsHfBh8bM2w%2BWuvzC%2FElA3eRWTo5mmV5pbJ5X3SYyDW3iWQyunn5zJJCnjtmzuYni8dsONZno%2BJ7oHZzCYLoJTDGSWgzHB0F1TVSnrIJURFAGXgk8%2FhwOrqT4BoGuty39s7kLYI%2FrYy3aHvL9pHLLA2LJR%2BRyMZUtodKMERDtTdXlcAprXLMK1XORhsGOheHysa5eTZHbwn0pgy%2BR1fphNAmaaKQ9VpsBTWOe3tDQ3rwKlsNcjOJPA5pgsUuANB6QgfnfB6l9lZfhRIMapm9qGcPl3V9NSSExf3Nb4YmqLpI0XMcH7UVMoS%2FS6%2B8HrtSQIlBjtbWbwsZ84E%2BZTxPtTEzr%2B6fI19aQoI9rrirucRXkfWI%2B3lUlCtLjgdI915tAjNQQE01HBw8CoQGRbI2%2FlklVG9qkgEOYBpzLS2b5XBqNASeN5UIGN8hlwC5W0Aw8TW%2F%2FrXXtx67HbA2Bh6YKlu0k4Fs1U5i03ZjQfUuQk7IOFCqGdwoNbM14eZydm3pPV%2FTmiYp1ApKdthFfSALyjQddbrIZbhj6gF8MfIcxiwEFFKtZeDCFzE9BBdDJnWQuHCCL6YSiBQ%3D%3D&AWSAccessKeyId=ASIAI7BXK7WPL6MEZTGQ&Expires=1413564768&Signature=iFM00YduFOrh7tBLSjmcgRRmz7o%3D');
//        $service->extract();
//        exit;

        $config = new Config($this->getConfig());
        $build = $this->params()->fromPost('build_id');
        $url = $this->params()->fromPost('package_url');
        $project = $this->params()->fromQuery('project_name');

        if (!empty($config) && !empty($url) && !empty($build) && !empty($project)) {
            $service = $this->getDeployManagerService();
            $service->deploy($build, $url, $project, $config);
        }
        return new ViewModel(array());
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
