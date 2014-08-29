<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 22/08/14
 * Time: 10:44
 */

namespace CphpAgent\Service;
use Zend\Crypt\Password\Bcrypt;

class ApiKeyManager {

    private $hash;

    function __construct($apiKey)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setSalt($this->generateSalt(Bcrypt::MIN_SALT_SIZE));
        $this->hash =  $bcrypt->create($apiKey);
    }

    public function getHash()
    {
        return $this->hash;
    }

    function verify($hash){
        return $hash === $this->hash;
    }

    private static function generateSalt($size) {
        $salt = '';
        for ($i = 0; $i < $size; $i++)
            $salt .= chr(rand(33, 126));
        return $salt;
    }
} 