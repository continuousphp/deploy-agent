<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:59
 */

namespace CphpAgent\database;


use Agent\Model\PhingParameter;

class PhingParameterTable extends SqliteProvider{

    const tableName = 'phing_parameter';
    protected static $createStmt='
                    CREATE TABLE IF NOT EXISTS phing_parameter (
                      repo_name VARCHAR(150) NOT NULL,
                      prop_name VARCHAR(50) NOT NULL,
                      prop_value VARCHAR(50) NOT NULL,
                      PRIMARY KEY (repo_name,prop_name),
                      FOREIGN KEY (repo_name) REFERENCES project(repo_name))';

    function __construct($dbAdapter)
    {
        parent::__construct($dbAdapter,self::tableName);
    }

    function createIfNotExist(){
        $this->getDbAdapter()->query(ProjectTable::$createStmt)->execute();
        $this->getDbAdapter()->query(self::$createStmt)->execute();
    }

    function convertRowToObject($row){
        return new PhingParameter($row['prop_name'],$row['prop_value']);
    }
} 