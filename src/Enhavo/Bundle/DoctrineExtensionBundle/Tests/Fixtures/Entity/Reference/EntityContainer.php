<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 16:02
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference;

/**
 * @Entity
 */
class EntityContainer
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Entity", cascade={"all"}, fetch="LAZY")
     */
    private $entity;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}
