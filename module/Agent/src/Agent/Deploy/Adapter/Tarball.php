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
        $stream = $this->streamFromUrl($tarUrl);
        $this->createFromResponseStream($stream,$dlDest);
    }

    function streamFromUrl($tarUrl)
    {
        $client = new Client($tarUrl, array(
            'sslverifypeer' => null,
            'sslallowselfsigned' => null,
        ));
        $client->setStream();
        return $client->send();
    }

    function createFromResponseStream(Zend_Http_Response_Stream $stream,$fileFolder)
    {
        FileSystem::mkdirp($fileFolder, 0777, true);
        $tarball = $fileFolder . $this->gzFileName;
        copy($stream->getStreamName(), $tarball);
        $fp = fopen($tarball, 'w');
        stream_copy_to_stream($stream->getStream(), $fp);
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