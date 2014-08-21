<?php

namespace Agent\Controller;

use Agent\Deploy\Adapter\Tarball;
use SebastianBergmann\Exporter\Exception;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Config\Config;

use Zend\Http\Client;
use Zend\Http\Response;
use Zend\Filter\Decompress;

use Agent\Service\FileSystem;

class AgentController extends AbstractActionController
{
    public function indexAction()
    {
        $settings = $this->getServiceLocator()->get('config');
        $config = new Config($settings['deployAgent']);
        $logger = new Logger();
        FileSystem::mkdirp($config->destPath, 0777, true);
        $writer = new Stream($config->destPath . 'deployment.log');
        $logger->addWriter($writer);
        $logger->info('######## START DEPLOYMENT ########');
        try {
            $logger->info('Downloading archive');
            $tarball = new Tarball($config->packageUrl, $config->destPath);
            $logger->info('Downloading archive [done]');

            $logger->info('Decompression');
            $tarball->decompress();
            $logger->info('Decompression [done]');

            $logger->info('Extraction');
            $tarball->extract();
            $logger->info('Extraction [done]');
        } catch (Exception $e) {
            var_dump($e->getMessage());
            $logger->err("An error has occurs during the deployment.");
            $logger->err("Details:" . $e->getMessage());
        }

        $logger->info('Delete temporary files');
        if (!is_null($tarball))
            $tarball->cleanTemporaryFile();
        $logger->info('Delete temporary files [done]');

        return new ViewModel(array());
    }
}
