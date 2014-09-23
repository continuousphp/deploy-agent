<?php

namespace CphpAgent\Service;

class ProjectService extends DoctrineEntityService
{
    public function getEntityRepository()
    {
        if (null === $this->entityRepository) {
            $this->setEntityRepository($this->getEntityManager()->getRepository('CphpAgent\Entity\Project'));
        }
        return $this->entityRepository;
    }

    public function save(){

    }
}