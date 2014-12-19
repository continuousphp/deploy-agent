<?php
/**
* LoginFormFactory.php
*
* @date        19/12/2014
* @author      Daniel Leivas <daniel@dasmuse.com>
* @file        LoginFormFactory.php
* @copyright   Copyright (c) continuousphp - All rights reserved
* @license     http://opensource.org/licenses/BSD-3-Clause
*/

namespace CphpAgent\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CphpAgent\Form\Login;

class LoginFormFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceManager) {
        $form = new Login('login');
        return $form;
    }
} 