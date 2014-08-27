<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 27/08/14
 * Time: 10:50
 */

namespace Agent\Service;


class UrlValidator {

    public static function isValid($url){
        $regex = '"^https?://continuousphp[-a-z0-9+&@#\\/%?=~_|!:,.;]*$"';
        return preg_match($regex,$url)===1;
    }
} 