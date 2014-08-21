<?php

class TarballTest extends PHPUnit_Framework_TestCase
{
    private static $tarUrl = 'http://github.com/zendframework/ZendSkeletonModule/tarball/master';
    private static $dest = '/temp_deploy_agent_test/';


    protected function tearDown()
    {
        rmdir(self::$dest);
    }

    public function testDownloadTar()
    {
        $tarball = new \Agent\Deploy\Adapter\Tarball(self::$tarUrl, self::$dest);
        $filePath = self::$dest . $tarball->getGzFileName();
        $this->assertTrue(is_file($filePath));
        AssertTrue(unlink($filePath));
    }

    public function testDecompress()
    {
        $tarball = new \Agent\Deploy\Adapter\Tarball(self::$tarUrl, self::$dest);
        $decompressPath = self::$dest . $tarball->getTarFileName();
        $this->assertFalse(is_file($decompressPath));
        $tarball->decompress();
        $this->assertTrue(is_file($decompressPath));
        unlink($decompressPath);
        unlink(self::$dest . $tarball->getGzFileName());
    }

    public function testExtract()
    {
        $tarball = new \Agent\Deploy\Adapter\Tarball(self::$tarUrl, self::$dest);
        $decompressPath = self::$dest . $tarball->getTarFileName();
        $tarball->decompress();
        $this->assertFalse(file_exists(self::$dest . 'zendframework-ZendSkeletonModule-2349bf5'));
        $tarball->extract();
        $this->assertTrue(file_exists(self::$dest . 'zendframework-ZendSkeletonModule-2349bf5'));
        array_map('unlink', glob(self::$dest));
    }
}