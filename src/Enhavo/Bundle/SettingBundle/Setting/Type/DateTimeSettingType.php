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

use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\SettingBundle\Entity\DateValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeSettingType
 *
 * @property ValueAccessSettingType $parent
 */
class DateTimeSettingType extends AbstractSettingType
{
    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if ($options['date'] && $options['time']) {
            $type = DateValue::TYPE_DATETIME;
        } elseif ($options['date']) {
            $type = DateValue::TYPE_DATE;
        } elseif ($options['time']) {
            $type = DateValue::TYPE_TIME;
        } else {
            throw new \InvalidArgumentException();
        }

        if (null === $settingEntity->getValue()) {
            $settingEntity->setValue(new DateValue($type, $settingEntity));

            if ($options['default']) {
                $settingEntity->getValue()->setValue(new \DateTime($options['default']));
            }

            return;
        }
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        /** @var \DateTime $date */
        $date = $value->getValue();
        if (null === $date) {
            return '';
        }

        if ($options['date'] && $options['time']) {
            return $date->format('d.m.y H:i');
        } elseif ($options['date']) {
            return $date->format('d.m.y');
        } elseif ($options['time']) {
            return $date->format('H:i');
        }

        return '';
    }

    public static function getName(): ?string
    {
        return 'datetime';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'time' => true,
            'date' => true,
            'form_type' => DateTimeType::class,
        ]);
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }
}
