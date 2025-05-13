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

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\SettingBundle\Entity\BasicValue;
use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BooleanSettingType
 *
 * @property ValueAccessSettingType $parent
 */
class BooleanSettingType extends AbstractSettingType
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * BooleanSettingType constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function init(array $options, $key = null)
    {
        $settingEntity = $this->parent->getSettingEntity($options, $key);

        if (null === $settingEntity->getValue()) {
            $settingEntity->setValue(new BasicValue(BasicValue::TYPE_BOOLEAN, $settingEntity));
            $settingEntity->getValue()->setValue($options['default']);

            return;
        }
    }

    public function getViewValue(array $options, $value, $key = null)
    {
        if ($value && $value->getValue()) {
            return $this->translator->trans('label.yes', [], 'EnhavoAppBundle');
        }

        return $this->translator->trans('label.no', [], 'EnhavoAppBundle');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_type' => BooleanType::class,
        ]);
    }

    public static function getName(): ?string
    {
        return 'boolean';
    }

    public static function getParentType(): ?string
    {
        return ValueAccessSettingType::class;
    }
}
