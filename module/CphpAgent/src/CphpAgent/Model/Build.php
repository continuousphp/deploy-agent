<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:29
 */

namespace CphpAgent\Model;


class Build {

    private $buildId;
    private $dateInSecond;

    function __construct($buildId, $dateInSecond)
    {
        $this->buildId = $buildId;
        $this->dateInSecond = $dateInSecond;
    }

    /**
     * @return string identifier of this build. (also the name of the folder where it will be saved)
     */
    public function getBuildId()
    {
        return $this->buildId;
    }

    /**
     * @return int the date of the creation of this build.
     */
    public function getDateInSecond()
    {
        return $this->dateInSecond;
    }

    /**
     * @return bool|string the date of the creation on string (day/month/years hours:minutes:seconds
     */
    public function getFormatedDate()
    {
        return date('d/m/Y H:i:s', $this->dateInSecond);
    }

} 