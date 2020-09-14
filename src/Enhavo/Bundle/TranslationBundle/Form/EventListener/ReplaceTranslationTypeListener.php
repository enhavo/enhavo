<?php


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
     * @param TranslationManager $translationManager
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $dataClass = $form->getConfig()->getDataClass();

        if (is_array($data) || is_scalar($data) || ($data === null && $dataClass === null)) {
            return;
        }

        // If the data is null but we have a data_class, we have to create it here,
        // because the translator can only work on concrete objects.
        // To wait until the form create the data is to late,
        // we need the data already in the TranslationType children.
        if ($data === null) {
            $data = new $dataClass;
        }

        if (!$this->translationManager->isTranslatable($data)) {
            return;
        }

        foreach ($form->all() as $property => $child) {
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
            'translation_domain' => $options['translation_domain']
        ]);
    }
}
