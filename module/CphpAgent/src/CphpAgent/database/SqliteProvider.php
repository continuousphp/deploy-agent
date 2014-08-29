<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/08/14
 * Time: 9:44
 */

namespace CphpAgent\database;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select;

abstract class SqliteProvider {
    private $dbAdapter;
    private $tableName;

    function __construct($dbAdapter, $tableName)
    {
        if($dbAdapter instanceof Adapter)
            $this->dbAdapter = $dbAdapter;
        $this->tableName = $tableName;
       $this->createIfNotExist();
    }

    /**
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    abstract function createIfNotExist();
    abstract function convertRowToObject($row);

    /**
     * Fetch the data matching the where clause.
     * @param $where array who's key are string matching columns and value the value wanted.
     *  The value can be null (resulting in: WHERE "key" IS NULL)
     * or value (resulting in: WHERE "KEY" = "value"
     * or array (resulting in: WHERE "KEY" IN (?,?,?)
     * @return array of object corresponding to the table
     */
    function select($where = null){
        $select = new Select($this->tableName);
        if(!is_null($where))
        $select->where($where);
        $resultSet = $this->executeSelect($select);
        $resultObj = array();
        foreach($resultSet as $row){
            $resultObj[] = $this->convertRowToObject($row);
        }
        return $resultObj;
    }

    function executeSelect(Select $select){
        $statement = $this->dbAdapter->createStatement();
        $select->prepareStatement($this->dbAdapter,$statement);
        return $statement->execute();
    }

    function insert($set){
        $insert = new Insert($this->tableName);
        $insert->values($set);
        $statement = $this->dbAdapter->createStatement();
        $insert->prepareStatement($this->dbAdapter,$statement);
        $statement->execute();
    }

    function delete($where){
        $delete = new Delete($this->tableName);
        $delete->where($where);
        $statement = $this->dbAdapter->createStatement();
        $delete->prepareStatement($this->dbAdapter,$statement);
        $statement->execute();
    }

} 