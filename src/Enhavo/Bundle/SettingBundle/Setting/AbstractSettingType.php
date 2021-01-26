<?php

namespace Enhavo\Bundle\SettingBundle\Setting;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Enhavo\Component\Type\AbstractType;

/**
 * Class AbstractSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting
 * @property SettingTypeInterface $parent
 */
abstract class AbstractSettingType extends AbstractType implements SettingTypeInterface
{
    public function init(array $options)
    {
        if ($this->parent) {
            return $this->parent->init($options);
        }
        return null;
    }

    public function getValue(array $options)
    {
        if ($this->parent) {
            return $this->parent->getValue($options);;
        }
        return null;
    }

    public function getFormType(array $options)
    {
        if ($this->parent) {
            return $this->parent->getFormType($options);
        }
        return null;
    }

    public function getFormTypeOptions(array $options)
    {
        if ($this->parent) {
            return $this->parent->getFormTypeOptions($options);
        }
        return [];
    }

    public function getViewValue(array $options, $value)
    {
        if ($this->parent) {
            return $this->parent->getViewValue($options, $value);
        }
        return (string) $value->getValue();
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
