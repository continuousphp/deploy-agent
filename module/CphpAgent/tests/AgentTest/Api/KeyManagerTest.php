<?php

namespace CphpAgentTest\Api;

use CphpAgent\Api\KeyManager;
use PHPUnit_Framework_TestCase;

class KeyManagerTest extends PHPUnit_Framework_TestCase
{
    private $apikey;
    protected function setUp()
    {
        $this->apikey = '';
        for ($i = 0; $i < 16; $i++)
            $this->apikey .= chr(rand(33, 126));
    }

    public function testConstruction()
    {
        $keyManager = new KeyManager();
        $keyManager->generate($this->apikey);
        $hash = $keyManager->getHash();

        $this->assertFalse(is_null($hash));
        $this->assertTrue(is_string($hash));
    }

    public function testVerify()
    {
        $keyManager = new KeyManager($this->apikey);
        $hash = $keyManager->getHash();
        $this->assertTrue($keyManager->verify($hash));
    }

    public function testVerifyNotCorrect()
    {
        $keyManager = new KeyManager($this->apikey);
        $hash = $keyManager->getHash();
        $last = substr($hash,-1);
        if($last != 'a'){
            $last = 'a';
        }else{
            $last = 'b';
        }
        $newHash = substr($hash,0,-1).$last;
        $this->assertFalse($keyManager->verify($newHash));
    }
}