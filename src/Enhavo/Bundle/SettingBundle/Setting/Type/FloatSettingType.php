<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property ValueAccessSettingType $parent
 */
class FloatSettingType extends AbstractSettingType
{
    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_FLOAT, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public static function getName(): ?string
    {
        return 'float';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_type' => NumberType::class
        ]);
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }
}
