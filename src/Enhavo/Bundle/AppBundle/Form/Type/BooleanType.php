<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class BooleanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label_true'] = $options['label_true'];
        $view->vars['label_false'] = $options['label_false'];

        $value = $view->vars['value'];
        $view->vars['checked'] = $value === '1';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices'   => array(
                '0' => 'false',
                '1' => 'true'
            ),
            'label_true' =>  'true',
            'label_false' => 'false',
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function getName()
    {
        return 'enhavo_boolean';
    }

    public function getParent()
    {
        return 'choice';
    }
}