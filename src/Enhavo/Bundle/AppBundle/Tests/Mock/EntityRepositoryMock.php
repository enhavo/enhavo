<?php

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class EntityRepositoryMock implements RepositoryInterface
{
    public $find;
    public $findAll;
    public $findBy;
    public $findOneBy;
    public $getClassName;
    public $createPaginator;
    public $add;
    public $remove;

    public function find($id)
    {
        return is_callable($this->find) ? call_user_func($this->find, $id) : $this->find;
    }

    public function findAll()
    {
        return is_callable($this->findAll) ? call_user_func($this->findAll) : $this->findAll;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return is_callable($this->findBy) ? call_user_func($this->findBy, $criteria, $orderBy, $limit, $offset) : $this->findBy;
    }

    public function findOneBy(array $criteria)
    {
        return is_callable($this->findOneBy) ? call_user_func($this->findOneBy, $criteria) : $this->findOneBy;
    }

    public function getClassName()
    {
        return is_callable($this->className) ? call_user_func($this->className) : $this->className;
    }

    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        return is_callable($this->createPaginator) ? call_user_func($this->createPaginator, $criteria, $sorting) : $this->createPaginator;
    }

    public function add(ResourceInterface $resource): void
    {
        is_callable($this->add) ? call_user_func($this->add, $resource) : null;
    }

    public function remove(ResourceInterface $resource): void
    {
        is_callable($this->remove) ? call_user_func($this->remove, $resource) : null;
    }
}
