<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity\Repository;

use Doctrine\ORM\EntityRepository;

abstract class SingleCrudRepository extends EntityRepository
{
    public function fetchEntity()
    {
        return \current($this->findAll());
    }

    public function saveEntity(array $data)
    {
        $entity = $this->fetchEntity();

        if (!$entity)
        {
            $entity = new $this->_entityName();
        }

        $entity->setFromArray($data);
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function deleteEntity()
    {
        $entity = $this->fetchEntity();

        if ($entity)
        {
            $this->_em->remove($entity);
            $this->_em->flush();
        }
    }
}
