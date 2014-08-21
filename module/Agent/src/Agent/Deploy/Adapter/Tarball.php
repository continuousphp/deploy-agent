<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 21/08/14
 * Time: 14:17
 */

namespace Agent\Deploy\Adapter;


use Agent\Service\FileSystem;
use Zend\Filter\Decompress;
use Zend\Http\Client;

class Tarball
{
    private $folder;
    private $gzFileName = 'tarball.tar.gz';
    private $tarFileName = 'tarball.tar';

    function __construct($tarUrl, $dest)
    {
        $this->folder = $dest;
        $this->downloadArchive($tarUrl, $dest);
    }

    private function downloadArchive($tarUrl, $dlDest)
    {
        $client = new Client($tarUrl, array(
            'sslverifypeer' => null,
            'sslallowselfsigned' => null,
        ));
        $client->setStream();
        $response = $client->send();

        if (!$response instanceof Zend_Http_Response_Stream) {

        }
        // copy stream
        FileSystem::mkdirp($dlDest, 0777, true);
        $tarball = $dlDest . $this->gzFileName;
        copy($response->getStreamName(), $tarball);
        $fp = fopen($tarball, 'w');
        stream_copy_to_stream($response->getStream(), $fp);
        fclose($fp);
    }

    function decompress()
    {
        $tarball = $this->folder . $this->gzFileName;
        if (is_file($tarball)) {
            /** Doc from http://unofficial-zf2.readthedocs.org/en/latest/modules/zend.filter.compress.html */
            $options = array(
                'adapter' => 'Gz',
                'options' => array(
                    'target' => $this->folder,
                )
            );
            $filter = new Decompress($options);
            $decompressed = $filter->filter($tarball);
            $tar = $this->folder . $this->tarFileName;
            file_put_contents($tar, $decompressed);
        }
    }

    function extract($destPath = null)
    {
        if (is_null($destPath))
            $destPath = $this->folder;
        else
            FileSystem::mkdirp($destPath, 0777, true);
        $tar = $this->folder . $this->tarFileName;
        if (is_file($tar)) {
            $options = array(
                'adapter' => 'Tar',
                'options' => array(
                    'target' => $destPath,
                )
            );
            $filter = new Decompress($options);
            $filter->setArchive($tar);
            if (is_file($tar))
                $decompressed = $filter->filter($tar);
        }
    }

    function cleanTemporaryFile()
    {
        $tar = $this->folder . $this->tarFileName;
        unlink($tar);
        $tarball = $this->folder . $this->gzFileName;
        unlink($tarball);
    }

    public function getTarFileName()
    {
        return $this->tarFileName;
    }

    public function getGzFileName()
    {
        return $this->gzFileName;
    }


} 