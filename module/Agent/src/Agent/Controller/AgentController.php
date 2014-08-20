<?php

namespace Agent\Controller;

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
        try {
            $dir = '/tmp/deploy-agent/';
            $gzFileName = 'tarball.tar.gz';
            $tarFileName = 'tarball.tar';
            $tarball = $dir . $gzFileName ;

            $client = new Client($config->packageUrl, array(
                'sslverifypeer' => null,
//                'outputstream'    => true,
                'sslallowselfsigned' => null,
            ));
            $client->setStream();
            $response = $client->send();

            // copy stream
            FileSystem::mkdirp($dir, 0777, true);
            copy($response->getStreamName(), $tarball);
            $fp = fopen($tarball, 'w');
            stream_copy_to_stream($response->getStream(), $fp);
            FileSystem::mkdirp($config->destPath, 0777, true);


            /** Doc from http://unofficial-zf2.readthedocs.org/en/latest/modules/zend.filter.compress.html */
            $options = array(
                'adapter' => 'Gz',
                'options' => array(
                    'target' => $config->destPath,
                )
            );
            $filter = new Decompress($options);
            $decompressed = $filter->filter($tarball);
            $tar = $dir . $tarFileName;
            file_put_contents($tar, $decompressed);
//            var_dump($decompressed);

            $options['adapter'] = 'Tar';
            $filter = new Decompress($options);
            $filter->setArchive($tar);
            if (is_file($tar))
                $decompressed = $filter->filter($tar);
            var_dump($decompressed);

        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }
        return new ViewModel(array());
    }
}
