<?php
/**
* Login.php
*
* @date        19/12/2014
* @author      Daniel Leivas <daniel@dasmuse.com>
* @file        Login.php
* @copyright   Copyright (c) continuousphp - All rights reserved
* @license     http://opensource.org/licenses/BSD-3-Clause
*/

namespace CphpAgent\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Login extends ProvidesEventsForm {

    public function __construct($name){
        parent::__construct($name);

        $this->setAttribute('class', 'navbar-form navbar-left');

        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'User',
            ],
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'type' => 'password',
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'options' => [
                'label' => 'Send',
            ],
            'attributes' => [
                'type' => 'submit',
            ],
        ]);

    }

} 