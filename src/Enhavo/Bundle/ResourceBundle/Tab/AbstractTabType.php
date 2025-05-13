<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tab;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\Type\BaseTabType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property TabTypeInterface $parent
 */
abstract class AbstractTabType extends AbstractType implements TabTypeInterface
{
    public function createViewData(array $options, Data $data, ?InputInterface $input = null): void
    {
    }

    public function getPermission(array $options, InputInterface $input): mixed
    {
        return $this->parent->getPermission($options, $input);
    }

    public function isEnabled(array $options, InputInterface $input): bool
    {
        return $this->parent->isEnabled($options, $input);
    }

    public static function getParentType(): ?string
    {
        return BaseTabType::class;
    }
}
