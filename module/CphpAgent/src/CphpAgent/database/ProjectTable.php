<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 11:19
 */

namespace CphpAgent\database;


use Agent\Model\ExcludeFolder;
use Agent\Model\Project;
use Zend\Db\Sql\Select;

class ProjectTable extends SqliteProvider{

    const tableName = 'project';
    protected  static $createStmt='
                    CREATE TABLE IF NOT EXISTS project (
                      repo_name VARCHAR(150) NOT NULL,
                      project_name VARCHAR(150) NOT NULL,
                      PRIMARY KEY (repo_name))';

    function __construct($dbAdapter)
    {
        parent::__construct($dbAdapter,self::tableName);
    }

    function createIfNotExist(){
        $this->getDbAdapter()->query(self::$createStmt)->execute();
        $this->getDbAdapter()->query(ExcludeFolderTable::$createStmt)->execute();
        $this->getDbAdapter()->query(PhingParameterTable::$createStmt)->execute();
        $this->getDbAdapter()->query(BuildTable::$createStmt)->execute();
    }

    function convertRowToObject($row){
        return new Project($row['repo_name'],$row['project_name']);
    }

    public function fetchAll(){
        return $this->fetchProject();
    }

    public function fetchByName($repoName){
        return $this->fetchProject(array('repo_name'=>$repoName));
    }

    public function fetchProject($where=null){
        $select = new Select(self::tableName);
        $select->join(BuildTable::tableName,self::tableName.'.repo_name = '.BuildTable::tableName.'.repo_name')
            ->join(PhingParameterTable::tableName,self::tableName.'.repo_name = '.PhingParameterTable::tableName.'.repo_name')
            ->join(ExcludeFolderTable::tableName,self::tableName.'.repo_name = '.ExcludeFolderTable::tableName.'.repo_name');
        if(!is_null($where)){
            $select->where($where);
        }
        $resultSet = $this->executeSelect($select);
        $currentProject = null;
        $projects = array();
        foreach($resultSet as $row){
            if(is_null($currentProject)){
                $currentProject =  $this->convertRowToObject($row);
            }elseif($currentProject->getProjectName() != $row['repo_name']){
                $projects[] = $currentProject;
                $currentProject =  $this->convertRowToObject($row);
            }
            $resultObj[] = $this->convertRowToObject($row);
        }
    }
} 