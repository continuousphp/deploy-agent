<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 12:01
 */

namespace CphpAgent\database;


use Agent\Model\ExcludeFolder;

class ExcludeFolderTable  extends SqliteProvider{

    const tableName = 'phing_parameter';
    protected static $createStmt='
                    CREATE TABLE IF NOT EXISTS exclude_folder (
                      repo_name VARCHAR(150) NOT NULL,
                      path VARCHAR(150) NOT NULL,
                      PRIMARY KEY (repo_name,path),
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
        return new ExcludeFolder($row['path']);
    }
} 