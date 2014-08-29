<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:55
 */

namespace CphpAgent\database;


use Agent\Model\Build;

class BuildTable extends SqliteProvider{

    const tableName = 'phing_parameter';
    protected static $createStmt='
                    CREATE TABLE IF NOT EXISTS build (
                      repo_name VARCHAR(150) NOT NULL,
                      build_id VARCHAR(150) NOT NULL,
                      build_date INT NOT NULL,
                      PRIMARY KEY (build_id),
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
        return new Build($row['build_id'],$row['build_date']);
    }
}