<?php

namespace AgentTest\Service;

use Agent\Service\UrlValidator;
use PHPUnit_Framework_TestCase;


class UrlValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testUrlContinuousUs()
    {
        $url = 'https://continuousphp-us-west-2.s3-us-west-2.amazonaws.com/17/refs/heads/dev/7a2e3b83-ed26';
        $this->assertTrue(UrlValidator::isValid($url));
    }

    public function testUrlNotContinuous()
    {
        $url = 'https://google-us-west-2.s3-us-west-2.amazonaws.com/17/refs/heads/dev/7a2e3b83-ed26';
        $this->assertFalse(UrlValidator::isValid($url));
    }

    public function testUrlContinuousWithInjection()
    {
        $url = "https://google-us-west-2.s3-us-west-2.amazonaws.com/17/refs/heads/dev/7a2e3b83-ed26');grant user";
        $this->assertFalse(UrlValidator::isValid($url));
    }
} 