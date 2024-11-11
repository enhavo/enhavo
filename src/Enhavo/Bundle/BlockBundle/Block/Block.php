<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property BlockTypeInterface $type
 */
class Block extends AbstractContainerType
{
    public function getModel()
    {
        return $this->type->getModel($this->options);
    }

    public function getForm()
    {
        return $this->type->getForm($this->options);
    }

    public function getFactory()
    {
        return $this->type->getFactory($this->options);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate($this->options);
    }

    public function getComponent()
    {
        return $this->type->getComponent($this->options);
    }

    public function getGroups()
    {
        return $this->type->getGroups($this->options);
    }

    public function getLabel()
    {
        return $this->type->getLabel($this->options);
    }

    public function createViewData(BlockInterface $block, $resource = null)
    {
        $data = new Data();
        /** @var BlockTypeInterface $parent */
        foreach($this->parents as $parent) {
            $parent->createViewData($block, $data, $resource, $this->options);
        }
        $this->type->createViewData($block, $data, $resource, $this->options);
        return $data->normalize();
    }

    public function finishViewData(BlockInterface $block, array $data, $resource = null)
    {
        $data = new Data($data);
        /** @var BlockTypeInterface $parent */
        foreach($this->parents as $parent) {
            $parent->finishViewData($block, $data, $resource, $this->options);
        }
        $this->type->finishViewData($block, $data, $resource, $this->options);
        return $data->normalize();
    }
}
