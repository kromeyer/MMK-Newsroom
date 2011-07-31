<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity\Repository;

use Doctrine\ORM\EntityRepository;

abstract class CrudRepository extends EntityRepository
{
    public function fetchNew()
    {
        return new $this->_entityName();
    }

    public function fetchEntity($id)
    {
        $entity = $this->find($id);

        if (! $entity instanceof $this->_entityName)
        {
            throw new \Exception('instanceof ' . $this->_entityName . ' failed');
        }

        return $entity;
    }

    public function fetchEntities()
    {
        return $this->findAll();
    }

    public function saveEntity(array $data)
    {
        if (isset($data['id']) && !empty($data['id']))
        {
            $entity = $this->fetchEntity($data['id']);
        }
        else
        {
            $entity = $this->fetchNew();
        }

        $entity->setFromArray($data);
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity->id;
    }

    public function deleteEntity($id)
    {
        $entity = $this->fetchEntity($id);

        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
