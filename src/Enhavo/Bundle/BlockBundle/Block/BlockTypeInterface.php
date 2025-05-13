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
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\TypeInterface;

/**
 * BlockTypeInterface
 *
 * @author gseidel
 */
interface BlockTypeInterface extends TypeInterface
{
    public function createViewData(BlockInterface $block, Data $data, $resource, array $options);

    public function finishViewData(BlockInterface $block, Data $data, $resource, array $options);

    public function getModel(array $options);

    public function getForm(array $options);

    public function getFactory(array $options);

    public function getTemplate(array $options);

    public function getComponent(array $options);

    public function getGroups(array $options);

    public function getLabel(array $options);
}
