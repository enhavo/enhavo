<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.09.18
 * Time: 20:35
 */

namespace Enhavo\Bundle\GridBundle\Context;

use Enhavo\Bundle\GridBundle\Model\Context;
use Enhavo\Bundle\GridBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Enhavo\Bundle\GridBundle\Model\ItemsAwareInterface;

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
        if($data instanceof ItemsAwareInterface) {
            $items = $data->getItems();
            foreach($items as $item) {
                if($item instanceof ContextAwareInterface) {
                    $context->addChild($this->build($item, $context));
                }
            }
        }

        if($data instanceof GridsAwareInterface) {
            $grids = $data->getGrids();
            foreach($grids as $grid) {
                if($grid instanceof ContextAwareInterface) {
                    $context->addChild($this->build($grid, $context));
                }
            }
        }

        if($data instanceof ItemInterface) {
            $itemType = $data->getItemType();
            $this->addChildren($itemType, $context);
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