<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:52
 */

namespace CphpAgent\Model;


class PhingParameter {
    private $propertyName;
    private $value;

    function __construct($propertyName, $value)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    /**
     * @return string the name of the property
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return string the value of the property
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getInCommandFormat(){
        return '-D'.$this->propertyName.'='.$this->value;
    }

} 