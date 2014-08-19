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
use Zend\Config;

class AgentController extends AbstractActionController
{
    public function indexAction()
    {
        $config = new Config(include 'config.php');

        $client = new Client();
        var_dump($config->server_url);
        $client->setUri($config->server_url);
        $client->setOptions(array(
            'maxredirects' => 1,
            'timeout'      => 30
        ));
        $request = new Request();

        var_dump('send');
        $response = $client->send($request);
        var_dump('get content');

        $body = $response->getContent();
        var_dump('put content');
        file_put_contents("temp.tar",$body);


        try {
            $phar = new \PharData('temp.tar');
            $phar->extractTo($config->dest_path, null, true);
        } catch (Exception $e) {

        }
        return new ViewModel(array());
    }
}
