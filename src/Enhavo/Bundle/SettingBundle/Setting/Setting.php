<?php

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * Class Setting
 * @package Enhavo\Bundle\SettingBundle\Setting
 * @property SettingTypeInterface $type
 */
class Setting extends AbstractContainerType
{
    public function getValue()
    {
        return $this->type->getValue($this->options);
    }

    public function init()
    {
        return $this->type->init($this->options);
    }

    public function getFormType()
    {
        return $this->type->getFormType($this->options);
    }

    public function getFormTypeOptions()
    {
        return $this->type->getFormTypeOptions($this->options);
    }

    public function getViewValue($value)
    {
        return $this->type->getViewValue($this->options, $value);
    }
}
