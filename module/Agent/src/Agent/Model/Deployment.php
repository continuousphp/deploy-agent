<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 25/08/14
 * Time: 17:22
 */

namespace Agent\Model;


class Deployment
{
    public $id;
    public $buildId;
    public $path;
    public $date;

    public function init($buildId,$path)
    {
        $this->buildId = $buildId;
        $this->path = $path;
        $this->date = time();
    }

    public function exchangeArray($data)
    {
        $this->$id = (!empty($data['id'])) ? $data['version'] : null;
        $this->$buildId = (!empty($data['buildId'])) ? $data['buildId'] : null;
        $this->$path = (!empty($data['path'])) ? $data['path'] : null;
        $this->$date = (!empty($data['date'])) ? $data['date'] : null;
    }
}