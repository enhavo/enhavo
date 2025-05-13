<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\Type\BaseActionType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property ActionTypeInterface $parent
 */
abstract class AbstractActionType extends AbstractType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
    }

    public function getPermission(array $options, ?object $resource = null): mixed
    {
        return $this->parent->getPermission($options, $resource);
    }

    public function isEnabled(array $options, ?object $resource = null): bool
    {
        return $this->parent->isEnabled($options, $resource);
    }

    public function getLabel(array $options): string
    {
        return $this->parent->getPermission($options);
    }

    public static function getParentType(): ?string
    {
        return BaseActionType::class;
    }
}
