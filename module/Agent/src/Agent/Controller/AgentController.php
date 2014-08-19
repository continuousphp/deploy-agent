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
        try {
            $client = new Client($config->packageUrl, array(
                'maxredirects' => 1,
                'timeout'      => 30,
                'sslverifypeer'      => false,
            ));

            $request = new Request();
            $response = $client->send($request);
            $body = $response->getContent();

            file_put_contents("temp.tar",$body);
            $phar = new \PharData('temp.tar');
            $phar->extractTo($config->destPath, null, true);
        }
        catch (Zend_Http_Client_Adapter_Exception $e) {
            var_dump($e->getMessage());
        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }
        return new ViewModel(array());
    }
}
