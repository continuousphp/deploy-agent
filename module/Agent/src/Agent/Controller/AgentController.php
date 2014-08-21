<?php

namespace Agent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Config\Config;

use Zend\Http\Client;
use Zend\Http\Request;

class AgentController extends AbstractActionController
{
    public function indexAction()
    {
        $settings = $this->getServiceLocator()->get('config');
        $config = new Config($settings['deployAgent']);

        $tarPath = $config->get('destPath') . 'deploy.tar';
        $apiKey = 000000;
        $other = 000000;
        $url = $config->get('packageUrl') . '?api_key=' . $apiKey . '&other' . $other;
        if(!file_exists($config->get('destPath'))){
            mkdir($config->get('destPath'));
        }
        file_put_contents($tarPath . '.gz',file_get_contents($url));
        try {
            $phar = new \PharData($tarPath . '.gz');
            $phar->decompress();
            $phar = new \PharData($tarPath);
            $phar->extractTo($config->get('destPath'), null, true);

            $lastDir = getcwd();
            chdir($config->get('destPath').$config->get('applicationName'));
            shell_exec('phing');
            chdir($lastDir);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        return new ViewModel(array());
    }
}
