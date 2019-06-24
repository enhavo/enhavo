<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 21:56
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

class BlockManager
{
    /**
     * @var Block[]
     */
    private $blocks = [];

    public function __construct(TypeCollector $collector, $configurations)
    {
        foreach($configurations as $name => $options) {
            /** @var BlockTypeInterface $configuration */
            $configuration = $collector->getType($options['type']);
            unset($options['type']);
            $block = new Block($configuration, $name, $options);
            $this->blocks[$name] = $block;
        }
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getBlock($name)
    {
        return $this->blocks[$name];
    }

    public function createViewData(NodeInterface $node, $resource = null)
    {
        /** @var BlockManager $manager */
        $manager = $this;
        $this->walk($node, function (NodeInterface $node) use ($manager, $resource) {
            if($node->getType() === NodeInterface::TYPE_BLOCK) {
                $node->setResource($resource);
                $viewData = $manager->getBlock($node->getName())->createViewData($node->getBlock(), $resource);
                $node->setViewData($viewData);
            }
        });

        $this->walk($node, function (NodeInterface $node) use ($manager, $resource) {
            if($node->getType() === NodeInterface::TYPE_BLOCK) {
                $viewData = $manager->getBlock($node->getName())->finishViewData($node->getBlock(), $node->getViewData(), $resource);
                $node->setViewData($viewData);
            }
        });
    }

    private function walk(NodeInterface $node, $callback)
    {
        $callback($node);
        foreach($node->getChildren() as $child) {
            $this->walk($child, $callback);
        }
    }
}
