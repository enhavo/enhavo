<?php

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Component\Type\AbstractType;

/**
 * Class AbstractSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting
 * @property SettingTypeInterface $parent
 */
abstract class AbstractSettingType extends AbstractType implements SettingTypeInterface
{
    public function init(array $options, $key = null)
    {
        if ($this->parent) {
            return $this->parent->init($options, $key);
        }
        return null;
    }

    public function getValue(array $options, $key = null)
    {
        if ($this->parent) {
            return $this->parent->getValue($options, $key);
        }
        return null;
    }

    public function getFormType(array $options, $key = null)
    {
        if ($this->parent) {
            return $this->parent->getFormType($options, $key);
        }
        return null;
    }

    public function getFormTypeOptions(array $options, $key = null)
    {
        if ($this->parent) {
            return $this->parent->getFormTypeOptions($options, $key);
        }
        return [];
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        if ($this->parent) {
            return $this->parent->getViewValue($options, $value, $key);
        }
        return (string) $value->getValue();
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
