<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 25/08/14
 * Time: 17:24
 */

namespace Agent\Model;


use Zend\Db\TableGateway\TableGateway;

class DeploymentTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $this->tableGateway->getAdapter()->query("CREATE TABLE IF NOT EXISTS deployment(
                                            path varchar(100) not null,
                                            date int not null,
                                            buildId varchar(100) not null)");
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getDeployment($version)
    {
        $version = (int)$version;
        $rowset = $this->tableGateway->select(array('version' => $version));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $version");
        }
        return $row;
    }

    public function saveDeployment(Deployment $deployment)
    {
        $data = array(
            'path' => $deployment->path,
            'date' => $deployment->date,
            'buildId' => $deployment->buildId,
        );

        $id = (int)$deployment->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getDeployment($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Deployment version does not exist');
            }
        }
    }

    public function deleteDeployment($version)
    {
        $this->tableGateway->delete(array('version' => (int)$version));
    }
} 