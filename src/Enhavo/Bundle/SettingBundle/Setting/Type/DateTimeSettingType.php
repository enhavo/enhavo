<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\SettingBundle\Entity\DateValue;
use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeSettingType extends AbstractSettingType
{
    public function init(array $options)
    {
        $settingEntity = $this->parent->getSettingEntity($options);

        if ($options['date'] && $options['time']) {
            $type = DateValue::TYPE_DATETIME;
        } elseif ($options['date']) {
            $type = DateValue::TYPE_DATE;
        } elseif ($options['time']) {
            $type = DateValue::TYPE_TIME;
        } else {
            throw new \InvalidArgumentException();
        }

        if ($settingEntity->getValue() === null) {
            $settingEntity->setValue(new DateValue($type, $settingEntity));

            if ($options['default']) {
                $settingEntity->getValue()->setValue(new \DateTime($options['default']));
            }
            return;
        }
    }

    public function getViewValue(array $options, ValueAccessInterface $value)
    {
        /** @var \DateTime $date */
        $date = $value->getValue();
        if ($date === null) {
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

    public function getFormType(array $options)
    {
        return DateTimeType::class;
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
        ]);
    }

    public static function getParentType(): ?string
    {
        return EntitySettingType::class;
    }
}
