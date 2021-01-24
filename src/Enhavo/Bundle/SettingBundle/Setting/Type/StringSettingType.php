<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class BooleanSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property EntitySettingType $parent
 */
class StringSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_VARCHAR, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public function getFormType(array $options)
    {
        return TextType::class;
    }

    public static function getName(): ?string
    {
        return 'string';
    }

    public static function getParentType(): ?string
    {
        return EntitySettingType::class;
    }
}
