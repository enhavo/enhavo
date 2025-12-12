<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Form\EventListener;

use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRendererInterface;

class ReplaceTranslationTypeListener implements EventSubscriberInterface
{
    /**
     * ResizeTranslationListener constructor.
     */
    public function __construct(
        private TranslationManager $translationManager,
        private FormRendererInterface $formRenderer
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
        ];
    }

    public function postSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();
        $dataClass = $form->getConfig()->getDataClass();

        if (is_array($data) || is_scalar($data) || (null === $data && null === $dataClass)) {
            return;
        }

        // If the data is null but we have a data_class, we have to create it here,
        // because the translator can only work on concrete objects.
        // To wait until the form create the data is too late,
        // we need the data already in the TranslationType children.
        $setData = false;
        if (null === $data) {
            $setData = true;
            $data = new $dataClass();
        }

        if (!$this->translationManager->isTranslatable($data)) {
            return;
        }

        // To prevent side effects we only setData in the form if necessary
        if ($setData) {
            $form->setData($data);
        }

        foreach ($form->all() as $property => $child) {
            // prevent reapply
            if (TranslationType::class === get_class($child->getConfig()->getType()->getInnerType())) {
                continue;
            }

            if ($this->translationManager->isFormTranslatable($data, $property)) {
                $this->replaceWithTranslationField($data, $property, $form, $child);

            } else { // replace all children to keep order as defined in the parent form type
                $this->replaceWithRegularField($property, $form, $child);
            }
        }
    }

    private function replaceWithTranslationField($data, string $property, FormInterface $form, FormInterface $child): void
    {
        $options = $child->getConfig()->getOptions();
        if (null === $options['label']) {
            $options['label'] = $this->formRenderer->humanize($child->getConfig()->getName());
        }

        $form->remove($property);
        $form->add($property, TranslationType::class, [
            'translation_data' => $data,
            'translation_property' => $property,
            'form_options' => $options,
            'form_type' => get_class($child->getConfig()->getType()->getInnerType()),
            'label' => $options['label'],
            'translation_domain' => $options['translation_domain'],
        ]);
    }


    private function replaceWithRegularField(string $property, FormInterface $form, FormInterface $child): void
    {
        $form->remove($property);
        $form->add($property, get_class($child->getConfig()->getType()->getInnerType()), $child->getConfig()->getOptions());
    }
}
