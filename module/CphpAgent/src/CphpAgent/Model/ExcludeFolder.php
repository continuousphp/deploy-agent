<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:54
 */

namespace CphpAgent\Model;


class ExcludeFolder {
    private $folder;

    function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return string the folder path to exclude
     */
    public function getFolder()
    {
        return $this->folder;
    }


} 