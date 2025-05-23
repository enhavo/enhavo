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

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\SettingBundle\Entity\TextValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TextSettingType
 *
 * @property ValueAccessSettingType $parent
 */
class TextSettingType extends AbstractSettingType
{
    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if (null === $settingEntity->getValue()) {
            $settingEntity->setValue(new TextValue($settingEntity));

            if (null !== $options['default']) {
                $settingEntity->getValue()->setValue($options['default']);
            }
        }
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        return strip_tags($value->getValue());
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }

    public static function getName(): ?string
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'wysiwyg' => false,
            'form_type' => TextareaType::class,
        ]);

        $resolver->setNormalizer('form_type', function ($options, $value) {
            if ($options['wysiwyg']) {
                return WysiwygType::class;
            }

            return $value;
        });
    }
}
