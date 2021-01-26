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
class IntegerSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_INT, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public static function getName(): ?string
    {
        return 'integer';
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
