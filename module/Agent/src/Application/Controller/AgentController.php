<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Agent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    public function indexAction()
    {
        $config = new Zend\Config\Config(include 'config.php');

        $client = new Client();
        $client->setUri($config->server_url);
        $client->setOptions(array(
            'maxredirects' => 1,
            'timeout'      => 30
        ));
        $request = new Request();
        $response = $client->send($request);

        $body = $response->getContent();
        file_put_contents("temp.tar",$body);


        try {
            $phar = new \PharData('temp.tar');
            $phar->extractTo($config->dest_path, null, true);
        } catch (Exception $e) {

        }
        return new ViewModel(array());
    }
}
