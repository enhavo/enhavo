<?php
/**
 * EntityMock.php
 *
 * @since 26/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Tests\Mock;

class EntityMock
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var array
     */
    private $entities;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function addEntity($entity)
    {
        $this->entities[] = $entity;
    }

    public function getEntities()
    {
        return $this->entities;
    }
}