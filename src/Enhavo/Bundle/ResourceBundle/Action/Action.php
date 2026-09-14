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
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property ActionTypeInterface   $type
 * @property ActionTypeInterface[] $parents
 */
class Action extends AbstractContainerType
{
    public function createViewData(?object $resource = null): array
    {
        $data = new Data();
        $data->set('key', $this->key);

        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $data, $resource);
        }

        $this->type->createViewData($this->options, $data, $resource);

        return $data->normalize();
    }

    public function getPermission(?object $resource = null)
    {
        return $this->type->getPermission($this->options, $resource);
    }

    public function isEnabled(?object $resource = null): bool
    {
        return $this->type->isEnabled($this->options, $resource);
    }
}
