<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Form\Type\ValueAccessType;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ValueAccessSettingType
 *
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
