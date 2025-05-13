<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\Type\BaseMenuType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property MenuTypeInterface $parent
 */
abstract class AbstractMenuType extends AbstractType implements MenuTypeInterface
{
    public function getPermission(array $options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public function createViewData(array $options, Data $data): void
    {
    }

    public static function getParentType(): ?string
    {
        return BaseMenuType::class;
    }
}
