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
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property TabTypeInterface $type
 * @property TabTypeInterface $parents
 */
class Tab extends AbstractContainerType
{
    public function createViewData(?InputInterface $input = null): array
    {
        $data = new Data();
        $data->set('key', $this->key);

        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $data, $input);
        }

        $this->type->createViewData($this->options, $data, $input);

        return $data->normalize();
    }

    public function getPermission(?InputInterface $input = null)
    {
        return $this->type->getPermission($this->options, $input);
    }

    public function isEnabled(?InputInterface $input = null): bool
    {
        return $this->type->isEnabled($this->options, $input);
    }
}
