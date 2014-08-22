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
            //TODO manage download stream error
        }
        // copy stream
        FileSystem::mkdirp($dlDest, 0777, true);
        $tarball = $dlDest . $this->gzFileName;
        copy($response->getStreamName(), $tarball);
        $fp = fopen($tarball, 'w');
        stream_copy_to_stream($response->getStream(), $fp);
        fclose($fp);
    }

    function extract($destPath = null)
    {
        if (is_null($destPath))
            $destPath = $this->folder;
        $tarball = $this->folder . $this->gzFileName;
        if (is_file($tarball)) {
            $tar = new \Archive_Tar($tarball, true);
            return $tar->extract($destPath);
        }
    }

    function cleanTemporaryFile()
    {
        $tarball = $this->folder . $this->gzFileName;
        if (is_file($tarball))
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