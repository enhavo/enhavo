<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 13:54
 */

namespace Enhavo\Bundle\BlockBundle\Block;

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
        return $this->type->createViewData($block, $resource, $this->options);
    }

    public function finishViewData(BlockInterface $block, array $viewData, $resource = null)
    {
        return $this->type->finishViewData($block, $viewData, $resource, $this->options);
    }
}
