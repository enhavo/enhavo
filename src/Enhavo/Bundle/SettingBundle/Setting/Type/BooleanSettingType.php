<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;

/**
 * Class BooleanSettingType
 * @package Enhavo\Bundle\SettingBundle\Setting\Type
 * @property EntitySettingType $parent
 */
class BooleanSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_BOOLEAN, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);
            return;
        }
    }

    public function getFormType(array $options)
    {
        return BooleanType::class;
    }

    public static function getName(): ?string
    {
        return 'boolean';
    }

    public static function getParentType(): ?string
    {
        return EntitySettingType::class;
    }
}
