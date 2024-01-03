<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Block extends AbstractContainerType
{
    /** @var BlockTypeInterface */
    protected $type;

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

    public function getTranslationDomain()
    {
        return $this->type->getTranslationDomain($this->options);
    }

    public function createViewData(BlockInterface $block, $resource = null)
    {
        $viewData = new ViewData();
        /** @var BlockTypeInterface $parent */
        foreach($this->parents as $parent) {
            $parent->createViewData($block, $viewData, $resource, $this->options);
        }
        $this->type->createViewData($block, $viewData, $resource, $this->options);
        return $viewData->normalize();
    }

    public function finishViewData(BlockInterface $block, array $data, $resource = null)
    {
        $viewData = new ViewData($data);
        /** @var BlockTypeInterface $parent */
        foreach($this->parents as $parent) {
            $parent->finishViewData($block, $viewData, $resource, $this->options);
        }
        $this->type->finishViewData($block, $viewData, $resource, $this->options);
        return $viewData->normalize();
    }
}
