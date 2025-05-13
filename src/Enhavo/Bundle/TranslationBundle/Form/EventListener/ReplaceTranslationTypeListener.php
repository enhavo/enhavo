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

class ReplaceTranslationTypeListener implements EventSubscriberInterface
{
    /** @var TranslationManager */
    private $translationManager;

    /**
     * ResizeTranslationListener constructor.
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
        ];
    }

    public function postSetData(FormEvent $event)
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

            if ($this->translationManager->isTranslatable($data, $property)) {
                $this->replaceChild($data, $property, $form, $child);
            }
        }
    }

    private function replaceChild($data, string $property, FormInterface $form, FormInterface $child)
    {
        $options = $child->getConfig()->getOptions();

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
}
