<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SettingManager $settingManager,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $translator = $this->translator;
        $settingManager = $this->settingManager;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($translator, $settingManager): void {
            $form = $event->getForm();
            /** @var Setting $settingEntity */
            $settingEntity = $event->getData();

            $form->add('name', TextType::class, [
                'mapped' => false,
                'data' => $translator->trans($settingEntity->getLabel(), [], $settingEntity->getTranslationDomain()),
                'attr' => [
                    'readonly' => true,
                ],
            ]);

            $form->add(
                'value',
                $settingManager->getFormType($settingEntity->getKey()),
                $settingManager->getFormTypeOptions($settingEntity->getKey())
            );
        });
    }
}
