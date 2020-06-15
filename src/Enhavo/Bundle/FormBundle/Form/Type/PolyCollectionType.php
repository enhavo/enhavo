<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:56
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\EventListener\ResizePolyFormListener;
use Enhavo\Bundle\FormBundle\Form\PolyCollection\EntryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * PolyCollectionType
 * @author gseidel <gseidel@enhavo.com>
 *
 * Heavily inspired by PolyCollectionType from InfiniteFormBundle
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

        $useTypesOptions = !empty($options['types_options']);

        $resizeListener = new ResizePolyFormListener(
            $prototypes,
            $useTypesOptions === true ? $options['types_options'] : $options['options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['type_name'],
            $options['index_property'],
            $useTypesOptions
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
    protected function buildPrototypes(FormBuilderInterface $builder, array $options)
    {
        $prototypes = array();
        $useTypesOptions = !empty($options['types_options']);

        foreach ($options['types'] as $key => $type) {
            $typeOptions = $options['options'];
            if ($useTypesOptions) {
                $typeOptions = [];
                if (isset($options['types_options'][$type])) {
                    $typeOptions = $options['types_options'][$type];
                }
            }

            $prototype = $this->buildPrototype(
                $builder,
                $options['prototype_name'],
                $type,
                $typeOptions
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
    protected function buildPrototype(FormBuilderInterface $builder, $name, $type, array $options)
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
        return 'infinite_form_polycollection';
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
            'type_name' => '_type',
            'options' => [],
            'types_options' => [],
            'index_property' => null,
        ));

        $resolver->setRequired(array(
            'types',
        ));

        $resolver->setAllowedTypes('types', 'array');
        $resolver->setNormalizer('options', $this->getOptionsNormalizer());
        $resolver->setNormalizer('types_options', $this->getTypesOptionsNormalizer());
    }

    private function getOptionsNormalizer()
    {
        return function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };
    }

    private function getTypesOptionsNormalizer()
    {
        return function (Options $options, $value) {
            foreach ($options['types'] as $type) {
                if (isset($value[$type])) {
                    $value[$type]['block_name'] = 'entry';
                }
            }

            return $value;
        };
    }
}
