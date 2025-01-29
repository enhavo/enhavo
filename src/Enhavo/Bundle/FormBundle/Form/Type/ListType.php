<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\FormBundle\Form\EventListener\MapSubmittedUuidDataListener;
use Enhavo\Bundle\FormBundle\Form\EventListener\SortableArrayFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new MapSubmittedUuidDataListener($options['uuid_property']));
        $builder->addEventSubscriber(new SortableArrayFormListener());
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['border'] = $options['border'];
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['allow_delete'] = $options['allow_delete'];
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['block_name'] = $options['block_name'];
        $view->vars['draggable_group'] = $options['draggable_group'];
        $view->vars['draggable_handle'] = $options['draggable_handle'];
        $view->vars['uuid_check'] = !!$options['uuid_property'];

        $array = $form->getData();
        if ($array instanceof Collection) {
            $array = $array->toArray();
        }

        if ($array != null) {
            end($array);
            $lastIndex = intval(key($array));
        } else {
            $lastIndex = -1;
        }

        $view->vars['index'] = $lastIndex+1;
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'border' => false,
            'sortable' => false,
            'uuid_property' => null,
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'draggable_group' => null,
            'draggable_handle' => '.drag-button',
        ));

        $resolver->setNormalizer('prototype_name', function(Options $options, $value) {
            return '__' . uniqid() . '__';
        });
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}
