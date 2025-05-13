<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Toolbar\Type\BaseToolbarWidgetType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property ToolbarWidgetTypeInterface $parent
 */
abstract class AbstractToolbarWidgetType extends AbstractType implements ToolbarWidgetTypeInterface
{
    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public function getPermission(array $options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function createViewData(array $options, Data $data): void
    {
    }

    public static function getParentType(): ?string
    {
        return BaseToolbarWidgetType::class;
    }
}
