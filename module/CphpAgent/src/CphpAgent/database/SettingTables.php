<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 10:46
 */

namespace CphpAgent\database;


use Agent\Model\Settings;

class SettingTable extends SqliteProvider{

    protected static $createStmt='
                    CREATE TABLE IF NOT EXISTS settings (
                      build_path VARCHAR(150) NOT NULL,
                      project_path VARCHAR(150) NOT NULL)';

    function __construct($dbAdapter)
    {
        parent::__construct($dbAdapter,'settings');
    }

    function createIfNotExist(){
        $this->getDbAdapter()->query(self::$createStmt)->execute();
    }

    function convertRowToObject($row){
        return new Settings($row['build_path'],$row['project_path']);
    }
} 