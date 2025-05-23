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

use Enhavo\Component\Type\AbstractType;

/**
 * Class AbstractSettingType
 *
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

    public function getGroup(array $options, $key = null)
    {
        if ($this->parent) {
            return $this->parent->getGroup($options, $key);
        }

        return null;
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
