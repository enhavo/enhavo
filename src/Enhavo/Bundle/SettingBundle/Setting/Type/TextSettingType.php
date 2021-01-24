<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\SettingBundle\Entity\TextValue;
use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new TextValue($settingEntity));

            if ($options['default'] !== null) {
                $settingEntity->getValue()->setValue($options['default']);
            }
        }
    }

    public function getFormType(array $options)
    {
        if ($options['wysiwyg']) {
            return WysiwygType::class;
        } else {
            return TextareaType::class;
        }
    }

    public function getViewValue(array $options, ValueAccessInterface $value)
    {
        return strip_tags($value->getValue());
    }

    public static function getParentType(): ?string
    {
        return EntitySettingType::class;
    }

    public static function getName(): ?string
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'wysiwyg' => false,
        ]);
    }
}
