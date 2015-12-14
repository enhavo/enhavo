<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;

class ListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['border'] = $options['border'];
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['sortable_property'] = $options['sortable_property'];
        $view->vars['allow_delete'] = $options['allow_delete'];
        $view->vars['block_name'] = $options['block_name'];
    }

    public function getName()
    {
        return 'enhavo_list';
    }

    public function getParent()
    {
        return 'collection';
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'border' => false,
            'sortable' => true,
            'sortable_property' => 'order',
        ));
    }
} 