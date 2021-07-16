<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property ValueAccessSettingType $parent
 */
class StringSettingType extends AbstractSettingType
{
    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_VARCHAR, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public static function getName(): ?string
    {
        return 'string';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_type' => TextType::class
        ]);
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }
}
