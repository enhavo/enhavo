<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Exception\SettingNotExists;
use Enhavo\Bundle\SettingBundle\Form\Type\ValueAccessType;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ValueAccessSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property BaseSettingType $parent
 */
class ValueAccessSettingType extends AbstractSettingType
{
    public function getSettingEntity($options, $key)
    {
        return $this->parent->getSettingEntity($options, $key);
    }

    public function getValue(array $options, $key = null)
    {
        $value = $this->parent->getValue($options, $key);
        return $value->getValue();
    }

    public function getFormType(array $options, $key = null)
    {
        return ValueAccessType::class;
    }

    public function getFormTypeOptions(array $options, $key = null)
    {
        return [
            'form_type' => $options['form_type'],
            'form_options' => $options['form_options'],
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_options' => [],
        ]);

        $resolver->setRequired(['form_type']);
    }

    public static function getParentType(): ?string
    {
        return BaseSettingType::class;
    }
}
