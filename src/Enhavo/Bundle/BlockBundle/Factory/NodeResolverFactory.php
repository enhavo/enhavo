<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 12:30
 */

namespace Enhavo\Bundle\BlockBundle\Factory;


class NodeResolverFactory
{
    /**
     * @var AbstractBlockFactory
     */
    private $factory;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var string
     */
    private $name;

    /**
     * NodeResolverFactory constructor.
     * @param AbstractBlockFactory $factory
     */
    public function __construct(AbstractBlockFactory $factory, NodeFactory $nodeFactory, $name)
    {
        $this->factory = $factory;
        $this->nodeFactory = $nodeFactory;
        $this->name = $name;
    }

    public function createNew()
    {
        $node = $this->nodeFactory->createNew();
        $node->setName($this->name);
        $node->setBlock($this->factory->createNew());
        return $node;
    }
}
