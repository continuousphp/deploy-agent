<?php
/**
 * AbstractFactory.php
 *
 * @date        19/10/14
 * @file        AbstractFactory.php
 * @author      Daniel Leivas <daniel@dasmuse.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Service;

use Zend\Crypt\Password\Bcrypt;
use CphpAgent\Entity;

/**
 * AbstractFactory
 *
 * @package     CphpAgent
 * @subpackage  Service
 * @author      Daniel Leivas <daniel@dasmuse.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
class User extends DoctrineEntityService
{
    /**
     * Get the User entity repository
     *
     * @return mixed User repository
     */
    public function getEntityRepository()
    {
        if (null === $this->entityRepository) {
            $this->setEntityRepository($this->getEntityManager()->getRepository('CphpAgent\Entity\User'));
        }
        return $this->entityRepository;
    }

    /**
     * Static function for checking hashed password (as required by Doctrine)
     *
     * @param  \CphpAgent\Entity\User   $user The identity object
     * @param  string                   $password  Password provided by the user, to verify
     *
     * @return boolean  If the password was correct or not
     */
    public static function verifyHashedPassword($user, $password)
    {
        $crypt = new Bcrypt();
        return $crypt->verify($password, $user->getPassword());
    }

}