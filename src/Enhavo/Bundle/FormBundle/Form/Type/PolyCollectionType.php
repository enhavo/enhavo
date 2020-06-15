<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:56
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\EventListener\ResizePolyFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * PolyCollectionType
 * Copied and customize from InfiniteFormBundle. Thanks for the assume work.
 *
 * @author gseidel
 */
class PolyCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototypes = $this->buildPrototypes($builder, $options);
        if ($options['allow_add'] && $options['prototype']) {
            $builder->setAttribute('prototypes', $prototypes);
        }

        $resizeListener = new ResizePolyFormListener(
            $prototypes,
            $options['entry_types_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['entry_type_name'],
            $options['index_property'],
            $options['entry_type_resolver']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * Builds prototypes for each of the form types used for the collection.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     *
     * @return array
     */
    private function buildPrototypes(FormBuilderInterface $builder, array $options)
    {
        $prototypes = array();

        foreach ($options['entry_types'] as $key => $type) {
            $prototype = $this->buildPrototype(
                $builder,
                $options['prototype_name'],
                $type,
                isset($options['entry_types_options'][$key]) ? $options['entry_types_options'][$key] : []
            );

            $prototypes[$key] = $prototype->getForm();
        }

        return $prototypes;
    }

    /**
     * Builds an individual prototype.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string                                       $name
     * @param string|FormTypeInterface                     $type
     * @param array                                        $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    private function buildPrototype(FormBuilderInterface $builder, $name, $type, array $options)
    {
        $prototype = $builder->create($name, $type, array_replace(array(
            'label' => $name.'label__',
        ), $options));

        return $prototype;
    }


    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['allow_delete'] = $options['allow_delete'];

        if ($form->getConfig()->hasAttribute('prototypes')) {
            $view->vars['prototypes'] = array_map(function (FormInterface $prototype) use ($view) {
                return $prototype->createView($view);
            }, $form->getConfig()->getAttribute('prototypes'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototypes')) {
            $multiparts = array_filter(
                $view->vars['prototypes'],
                function (FormView $prototype) {
                    return $prototype->vars['multipart'];
                }
            );

            if ($multiparts) {
                $view->vars['multipart'] = true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'enhavo_poly_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => true,
            'prototype_name' => '__name__',
            'index_property' => null,
            'entry_types_options' => [],
            'entry_type_name' => '_key',
            'entry_type_resolver' => null
        ));

        $resolver->setRequired(array(
            'entry_types',
        ));

        $resolver->setAllowedTypes('entry_types', 'array');
    }
}
