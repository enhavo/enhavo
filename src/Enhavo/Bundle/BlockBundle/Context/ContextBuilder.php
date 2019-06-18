<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.09.18
 * Time: 20:35
 */

namespace Enhavo\Bundle\BlockBundle\Context;

use Enhavo\Bundle\BlockBundle\Model\Context;
use Enhavo\Bundle\BlockBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\BlockBundle\Model\ContainerAwareInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\BlocksAwareInterface;

class ContextBuilder
{
    /**
     * @param ContextAwareInterface $data
     * @param Context $parent
     * @return Context
     */
    public function build(ContextAwareInterface $data, Context $parent = null)
    {
        $context = new Context();
        $context->setParent($parent);
        $context->setData($data);
        $data->setContext($context);

        $this->addChildren($data, $context);
        $this->setNeighbours($context);

        return $context;
    }

    private function addChildren($data, Context $context)
    {
        if($data instanceof BlocksAwareInterface) {
            $blocks = $data->getBlocks();
            foreach($blocks as $block) {
                if($block instanceof ContextAwareInterface) {
                    $context->addChild($this->build($block, $context));
                }
            }
        }

        if($data instanceof ContainerAwareInterface) {
            $containers = $data->getContent();
            foreach($containers as $container) {
                if($container instanceof ContextAwareInterface) {
                    $context->addChild($this->build($container, $context));
                }
            }
        }

        if($data instanceof BlockInterface) {
            $blockType = $data->getBlockType();
            $this->addChildren($blockType, $context);
        }
    }

    private function setNeighbours(Context $context)
    {
        /** @var Context $before */
        $before = null;
        foreach($context->getChildren() as $child) {
            if($before) {
                $before->setNext($child);
                $child->setBefore($before);
            }
            $before = $child;
        }
    }
}