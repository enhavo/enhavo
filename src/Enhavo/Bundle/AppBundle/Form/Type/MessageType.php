<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MessageType extends AbstractType
{
    const MESSAGE_TYPE_INFO = 'info';
    const MESSAGE_TYPE_WARNING = 'warning';

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['message'] = $this->translator->trans($options['message'], $options['parameters'], $options['translation_domain']);
        $view->vars['type'] = $options['type'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'message' => '',
            'type' => self::MESSAGE_TYPE_INFO,
            'parameters' => [],
            'translation_domain' => 'EnhavoAppBundle',
            'label' => ' '
        ));
    }

    public function getName()
    {
        return 'enhavo_message';
    }
}