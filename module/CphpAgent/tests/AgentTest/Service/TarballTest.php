<?php

namespace CphpAgentTest\Service;

use Agent\Deploy\Adapter\Tarball;
use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\content\LargeFileContent;


class TarballTest extends PHPUnit_Framework_TestCase
{
    private static $tarUrl = 'http://github.com/zendframework/ZendSkeletonModule/tarball/master'; // 'http://dasmuse.com/deploy-agent.tar.gz';
    private static $dest = '/tmp/temporary_deploy_agent_test/';
    private static $projectName = 'zendframework-ZendSkeletonModule-2349bf5/';

    protected $hugeFile;
    private $root;

    protected function setUp()
    {
        $this->root = vfsStream::setup('deploy-agent/');
        $this->hugeFile = vfsStream::newFile('deploy-agent.tar.gz')
            ->withContent(LargeFileContent::withGigabytes(1))
            ->at($this->root);
        vfsStream::copyFromFileSystem('/tmp/testing_file/');
    }

    public function testDownloadTar()
    {
        $this->assertFalse($this->root->hasChild('tarball.tar.gz'));
        $tarball = new Tarball(vfsStream::url('deploy-agent/'));
        $tarball->downloadArchive(self::$tarUrl);
        $this->assertTrue($this->root->hasChild('tarball.tar.gz'));
    }

//    public function testExtract()
//    {
//        $tarball = new Tarball(vfsStream::url('deploy-agent/'));
//        $tarball->downloadArchive(self::$tarUrl);
//        $this->assertFalse($this->root->hasChild(self::$projectName));
//        $tarball->setGzFileName('testingfile.tar.gz');
//        $tarball->extract();
//        foreach ($this->root->getChildren() as $key => $file) {
//            var_dump($file->getName());
//        }
//        $this->assertTrue($this->root->hasChild(self::$projectName));
//    }
//
//    public function testExtractElseWhere()
//    {
//        $tarball = new Tarball(self::$tarUrl, self::$dest);
//        $projectPath = self::$dest . 'otherFolder/far/far/away/' . self::$projectName;
//        $this->assertFalse(file_exists($projectPath));
//        $tarball->extract(self::$dest . 'otherFolder/far/far/away/');
//        $this->assertTrue(file_exists($projectPath));
//    }
//
//    public function testHugeFile()
//    {
//        $tarball = new Tarball($this->hugeFile, self::$dest);
//        $this->assertFalse(file_exists(self::$dest . 'easyeclipse-php-1.2.2.2'));
//        $tarball->extract();
//        $this->assertTrue(file_exists(self::$dest . 'easyeclipse-php-1.2.2.2'));
//    }
//
//    private function rrmdir($dir)
//    {
//        if (is_dir($dir)) {
//            $objects = scandir($dir);
//            foreach ($objects as $object) {
//                if ($object != "." && $object != "..") {
//                    if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
//                }
//            }
//            reset($objects);
//            rmdir($dir);
//        }
//    }
}