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

use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class EntitySettingType extends AbstractSettingType
{
    public function getFormType(array $options, $key = null)
    {
        return EntityType::class;
    }

    public function getFormTypeOptions(array $options, $key = null)
    {
        return [
            'class' => $options['class'],
            'multiple' => false,
            'expanded' => $options['expanded'],
        ];
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        if (null === $value) {
            return '';
        }

        if ($options['property_path']) {
            return (new PropertyAccessor())->getValue($value, $options['property_path']);
        }

        return (string) $value;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'property_path' => null,
        ]);

        $resolver->setRequired(['class']);
    }

    public static function getName(): ?string
    {
        return 'entity';
    }

    public static function getParentType(): ?string
    {
        return BaseSettingType::class;
    }
}
