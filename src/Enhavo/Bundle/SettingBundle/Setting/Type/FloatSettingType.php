<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Class BooleanSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property EntitySettingType $parent
 */
class FloatSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_FLOAT, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public function getFormType(array $options)
    {
        return NumberType::class;
    }

    public static function getName(): ?string
    {
        return 'float';
    }

    public static function getParentType(): ?string
    {
        return EntitySettingType::class;
    }
}
