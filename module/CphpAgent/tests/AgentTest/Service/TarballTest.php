<?php

namespace AgentTest\Service;

use CphpAgent\Deploy\Adapter\Tarball;
use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\content\LargeFileContent;


class TarballTest extends PHPUnit_Framework_TestCase
{
    private static $tarUrl = 'http://github.com/zendframework/ZendSkeletonModule/tarball/master';
    private static $dest = '/tmp/temporary_deploy_agent_test/';
    private static $projectName = 'zendframework-ZendSkeletonModule-2349bf5/';

    protected $hugeFile;
    protected $tarball;

    protected function setUp()
    {
        $root = vfsStream::setup();
        $this->hugeFile = vfsStream::newFile('deploy-agent.tar.gz')
            ->withContent(LargeFileContent::withGigabytes(1))
            ->at($root);
        $this->rrmdir(self::$dest);
        $this->tarball = new Tarball(self::$dest);

    }

    protected function tearDown()
    {
        $this->rrmdir(self::$dest);
    }

    public function testDownloadTar()
    {
        $filePath = self::$dest . $this->tarball->getGzFileName();
        $this->tarball->downloadArchive(self::$tarUrl);
        $this->assertTrue(is_file($filePath));
    }

    public function testExtract()
    {
        $projectPath = self::$dest . self::$projectName;
        $this->assertFalse(file_exists($projectPath));
        $this->tarball->downloadArchive(self::$tarUrl);
        $this->tarball->extract();
        $this->assertTrue(file_exists($projectPath));
    }

    public function testExtractElseWhere()
    {
        $projectPath = self::$dest . 'otherFolder/far/far/away/' . self::$projectName;
        $this->assertFalse(file_exists($projectPath));
        $this->tarball->downloadArchive(self::$tarUrl);
        $this->tarball->extract(self::$dest . 'otherFolder/far/far/away/');
        $this->assertTrue(file_exists($projectPath));
    }

    public function testHugeFile()
    {
//        $mockResponse = $this->getMock('Zend\Http\Response\Stream');
//        $mockResponse->expects($this->once())
//            ->method('getStream')
//            ->will($this->returnValue($this->hugeFile));
//        $this->tarball->createFromResponseStream($mockResponse, self::$dest);
//        $this->assertFalse(file_exists(self::$dest . 'easyeclipse-php-1.2.2.2'));
//        $this->tarball->extract();
//        $this->assertTrue(file_exists(self::$dest . 'easyeclipse-php-1.2.2.2'));
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}