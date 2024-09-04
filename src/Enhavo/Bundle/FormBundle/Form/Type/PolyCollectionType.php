<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:56
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\EventListener\ResizePolyFormListener;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PolyCollectionType
 * Copied and customize from InfiniteFormBundle. Thanks for the awesome work.
 *
 * @author gseidel
 */
class PolyCollectionType extends AbstractType
{
    public function __construct(
        private PrototypeManager $prototypeManager
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['prototype']) {
            if (!$this->prototypeManager->hasStorage($options['prototype_storage'])) {
                $this->prototypeManager->initStorage($options['prototype_storage']);
                $this->buildPrototypes($builder, $options);
            }
        }

        $resizeListener = new ResizePolyFormListener(
            $this->prototypeManager,
            $options['prototype_storage'],
            [],
            $options['entry_types_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['entry_type_name'],
            $options['entry_type_resolver']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * Builds prototypes for each of the form types used for the collection.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    private function buildPrototypes(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['entry_types'] as $key => $type) {
            $this->prototypeManager->buildPrototype(
                $builder,
                $options['prototype_storage'],
                $type,
                isset($options['entry_types_options'][$key]) ? $options['entry_types_options'][$key] : [],
                ['key' => $key],
                isset($options['entry_types_prototype_data'][$key]) ? $options['entry_types_prototype_data'][$key] : null
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['allow_delete'] = $options['allow_delete'];
        $view->vars['add_label'] = $options['add_label'];
        $view->vars['entry_labels'] = $this->buildEntryLabels($options);

        $view->vars['poly_collection_config'] = [
            'entryKeys' => $this->buildEntryKeys($options),
            'prototypeStorage' => $options['prototype_storage'],
            'collapsed' => $options['collapsed'],
            'confirmDelete' => $options['confirm_delete'],
        ];

        $view->vars['custom_name_property'] = $options['custom_name_property'];
    }

    private function buildEntryKeys(array $options)
    {
        $keys = [];
        foreach ($this->prototypeManager->getPrototypes($options['prototype_storage']) as $prototype) {
            $keys[] = $prototype->getParameters()['key'];
        }

        if ($options['entry_type_filter'] !== null) {
            return call_user_func($options['entry_type_filter'], $keys, $this);
        }
        return $keys;
    }

    private function buildEntryLabels(array $options)
    {
        $choices = [];
        foreach ($options['entry_types_options'] as $key => $entryTypeOption) {
            $choices[] = [
                'key' => $key,
                'label' => $entryTypeOption['label']
            ];
        }
        return $choices;
    }


    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $this->prototypeManager->buildView($view, $form, $options);
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
        $resolver->setDefaults([
            'allow_add' => true,
            'allow_delete' => true,
            'collapsed' => false,
            'prototype' => true,
            'prototype_name' => '__name__',
            'entry_types_options' => [],
            'entry_type_name' => '_key',
            'entry_type_resolver' => null,
            'entry_type_filter' => null,
            'entry_types_prototype_data' => [],
            'prototype_storage' => null,
            'by_reference' => false,
            'custom_name_property' => null,
            'add_label' => '',
            'confirm_delete' => false
        ]);

        $resolver->setRequired(array(
            'entry_types',
        ));

        $resolver->setAllowedTypes('entry_types', 'array');

        $this->prototypeManager->normalizePrototypeStorage('prototype_storage', $resolver);
    }
}
