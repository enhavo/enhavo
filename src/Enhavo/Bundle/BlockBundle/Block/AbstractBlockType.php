<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\BlockBundle\Block\Type\BaseBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractType;

/**
 * @property BlockTypeInterface $parent
 */
abstract class AbstractBlockType extends AbstractType implements BlockTypeInterface
{
    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
    }

    public function finishViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
    }

    public function getModel(array $options)
    {
        return $this->parent->getModel($options);
    }

    public function getForm(array $options)
    {
        return $this->parent->getForm($options);
    }

    public function getFactory(array $options)
    {
        return $this->parent->getFactory($options);
    }

    public function getTemplate(array $options)
    {
        return $this->parent->getTemplate($options);
    }

    public function getComponent(array $options)
    {
        return $this->parent->getComponent($options);
    }

    public function getGroups(array $options)
    {
        return $this->parent->getGroups($options);
    }

    public function getLabel(array $options)
    {
        return $this->parent->getLabel($options);
    }

    public static function getParentType(): ?string
    {
        return BaseBlockType::class;
    }
}
