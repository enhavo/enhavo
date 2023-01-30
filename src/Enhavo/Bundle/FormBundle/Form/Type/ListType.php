<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        // save origin value that was set as data
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use (&$data) {
            $item = $event->getData();
            $data = $item;
            return;
        });

        // reorder if origin was array
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$data){
            $item = $event->getData();
            if((is_array($data) || $data === null) && is_array($item)) {
                $itemKeys = array_keys($item);
                $copyValues = array_values($item);
                sort($itemKeys);
                $result = array();
                for($i = 0; $i < count($itemKeys); $i++) {
                    $result[intval($itemKeys[$i])] = $copyValues[$i];
                }
                $event->setData($result);
            }
            return;
        });

        // reindex data, so array starts with index 0
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use (&$data){
            $items = $event->getData();
            if((is_array($data) || $data === null) && is_array($items)) {
                $result = [];
                $i = 0;
                foreach($items as $item) {
                    $result[$i] = $item;
                    $i++;
                }
                $event->setData($result);
            }
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
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['block_name'] = $options['block_name'];
        $view->vars['draggable_group'] = $options['draggable_group'];
        $view->vars['draggable_handle'] = $options['draggable_handle'];

        $lastIndex = null;
        $array = $form->getData();
        if ($array instanceof Collection) {
            $array = $array->toArray();
        }
        if($array != null) {
            end($array);
            $lastIndex = intval(key($array));
        } else {
            $lastIndex = -1;
        }

        $view->vars['index'] = $lastIndex+1;
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    public function getBlockPrefix()
    {
        return 'enhavo_list';
    }

    public function getParent()
    {
        return CollectionType::class;
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
            'allow_delete' => true,
            'draggable_group' => null,
            'draggable_handle' => '.drag-button',
        ));

        $resolver->setNormalizer('prototype_name', function(Options $options, $value) {
            return '__' . uniqid() . '__';
        });
    }
}
