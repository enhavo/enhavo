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
class Entity
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

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $nodeName;

    /**
     * @Column(type="integer",  nullable=true)
     */
    private $nodeId;

    /** @var NodeInterface */
    private $node;

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

    public function getNodeName()
    {
        return $this->nodeName;
    }

    public function setNodeName($nodeName): void
    {
        $this->nodeName = $nodeName;
    }

    public function getNodeId()
    {
        return $this->nodeId;
    }

    public function setNodeId($nodeId): void
    {
        $this->nodeId = $nodeId;
    }

    public function getNode(): ?NodeInterface
    {
        return $this->node;
    }

    public function setNode(?NodeInterface $node): void
    {
        $this->node = $node;
    }
}
