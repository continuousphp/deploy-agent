<?php

namespace CphpAgentTest\Service;

use CphpAgent\Service\ApiKeyManager;
use PHPUnit_Framework_TestCase;

class ApiKeyManagerTest extends PHPUnit_Framework_TestCase
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
        $keyManager = new ApiKeyManager($this->apikey);
        $hash = $keyManager->getHash();
        $this->assertFalse(is_null($hash));
        $this->assertTrue(is_string($hash));
    }

    public function testVerify()
    {
        $keyManager = new ApiKeyManager($this->apikey);
        $hash = $keyManager->getHash();
        $this->assertTrue($keyManager->verify($hash));
    }

    public function testVerifyNotCorrect()
    {
        $keyManager = new ApiKeyManager($this->apikey);
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