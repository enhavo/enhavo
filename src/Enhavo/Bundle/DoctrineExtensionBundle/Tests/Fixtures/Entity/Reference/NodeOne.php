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
class NodeOne implements NodeInterface
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function getId()
    {
        return $this->id;
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
