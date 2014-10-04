<?php
/**
 * KeyManager.php
 *
 * @date        03/09/2014
 * @author      Daniel Leivas <daniel@dasmuse.com>
 * @file        KeyManager.php
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Api;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class        KeyManager
 *
 * @package     CphpAgent\Api
 * @author      Daniel Leivas <daniel@dasmuse.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
class KeyManager {
    /**
     * @var string
     */
    private $hash;

    /**
     * Generate Api Key
     *
     * @param $apiKey
     * @return string
     */
    public function generate($apiKey)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setSalt($this->generateSalt(Bcrypt::MIN_SALT_SIZE));
        $this->hash =  $bcrypt->create($apiKey);

        return $this->hash;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Check if hash is correct
     *
     * @param $hash
     * @return bool
     */
    function verify($hash){
        return $hash === $this->hash;
    }

    /**
     * Generate Salt
     *
     * @param $size
     * @return string
     */
    private static function generateSalt($size) {
        $salt = '';
        for ($i = 0; $i < $size; $i++)
            $salt .= chr(rand(33, 126));
        return $salt;
    }
} 