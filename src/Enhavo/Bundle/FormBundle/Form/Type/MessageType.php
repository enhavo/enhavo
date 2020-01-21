<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class MessageType
 * @package Enhavo\Bundle\FormBundle\Form\Type
 * @deprecated should be form extension or use twig file to print message
 */
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
            'translation_domain' => 'EnhavoFormBundle',
            'label' => ' '
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_message';
    }
}
