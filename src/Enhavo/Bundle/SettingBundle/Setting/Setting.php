<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Component\Type\AbstractContainerType;

/**
 * Class Setting
 *
 * @property SettingTypeInterface $type
 */
class Setting extends AbstractContainerType
{
    public function getValue()
    {
        return $this->type->getValue($this->options, $this->key);
    }

    public function init()
    {
        return $this->type->init($this->options, $this->key);
    }

    public function getFormType()
    {
        return $this->type->getFormType($this->options, $this->key);
    }

    public function getFormTypeOptions()
    {
        return $this->type->getFormTypeOptions($this->options, $this->key);
    }

    public function getViewValue($value)
    {
        return $this->type->getViewValue($this->options, $value, $this->key);
    }

    public function getGroup()
    {
        return $this->type->getGroup($this->options, $this->key);
    }
}
