<?php

namespace CphpAgent\Model;


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
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->buildId = (!empty($data['buildId'])) ? $data['buildId'] : null;
        $this->path = (!empty($data['path'])) ? $data['path'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
    }
}