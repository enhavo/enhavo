<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = null;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$data){
            $item = $event->getData();
            if(is_array($data) && is_array($item)) {
                $itemKeys = array_keys($item);
                $copyValues = array_values($item);
                sort($itemKeys);
                $result = array();
                for($i = 0; $i < count($itemKeys); $i++) {
                    $result[$itemKeys[$i]] = $copyValues[$i];
                }

                $event->setData($result);
            }
            return;
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use (&$data){
            $item = $event->getData();
            $data = $item;
            return;
        });
    }
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
        $lastIndex = null;
        $array = $form->getData();
        if($array != null) {
            end($array);
            $lastIndex = key($array);
        } else {
            $lastIndex = -1;
        }

        $view->vars['index'] = $lastIndex+1;
        $view->vars['prototype_name'] = $options['prototype_name'];
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
            'sortable' => false,
            'sortable_property' => 'position',
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true
        ));

        $resolver->setNormalizer('prototype_name', function(Options $options, $value) {
            return '__' . $options['type'] . '__';
        });
    }
} 