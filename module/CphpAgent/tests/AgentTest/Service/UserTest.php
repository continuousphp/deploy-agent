<?php

namespace AgentTest\Service;

use CphpAgent\Entity\User;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\Bcrypt;

class UserTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $user = new User();
        $crypt = new Bcrypt();
        $password = 'test';
        $cryptedPassword = $crypt->create($password);
        $data  = array(
            'id'     => 123,
            'username'  => 'some_username',
            'password'  => $cryptedPassword,
        );
        $user->exchangeArray($data);

        $this->assertSame(
            $data['id'],
            $user->getId(),
            '"id" was not set correctly'
        );
        $this->assertSame(
            $data['username'],
            $user->getUsername(),
            '"username" was not set correctly'
        );
        $this->assertSame(
            $data['password'],
            $user->getPassword(),
            '"title" was not set correctly'
        );
    }

}