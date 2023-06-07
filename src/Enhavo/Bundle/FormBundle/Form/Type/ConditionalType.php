<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 06:56
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionalType extends AbstractVueType implements DataMapperInterface
{
    private bool $keyChanged = false;

    public function __construct(
        private VueForm $vueForm,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototypes = [];
        foreach ($options['entry_types'] as  $key => $type) {
            $prototypeOptions = isset($options['entry_types_options'][$key]) ? $options['entry_types_options'][$key] : [];
            $prototypes[$key] = $builder->create($options['prototype_name'], $type, $prototypeOptions)->getForm();
        }
        $builder->setAttribute('prototypes', $prototypes);


        $builder->add('conditional', HiddenType::class, []);
        $builder->add('key', HiddenType::class, []);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $data = $event->getData();

            $key = $this->resolveKey($options, $data, $form);
            if ($key) {
                $this->addConditional($options, $form,  $key);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $data = $event->getData();

            $key = $data['key'];
            if ($form->getData()) {
                // detected if key is changed, because we need create a new object out of the new form type
                $originalKey = $this->resolveKey($options, $form->getData(), $form);
                $this->keyChanged = false;
                if ($originalKey != $key) {
                    $this->keyChanged = true;
                }
            }

            $this->addConditional($options, $form,  $key);
        });

        $builder->setDataMapper($this);
    }

    private function resolveKey($options, $data, Form $form)
    {
        if (is_scalar($options['entry_type_resolver'])) {
            return $options['entry_type_resolver'];
        } elseif (is_callable($options['entry_type_resolver'])) {
            return call_user_func($options['entry_type_resolver'], $data, $form);
        }

        throw new \Exception(sprintf('The option "entry_type_resolver" must be callable or scalar'));
    }

    private function addConditional($options, $form, $key)
    {
        if (!array_key_exists($key, $options['entry_types'])) {
            throw new \Exception(sprintf('Key "%s" not exists in entry types "%s"', $key, join(',', $options['entry_types'])));
        }

        $type = $options['entry_types'][$key];
        $formOptions = isset($options['entry_types_options'][$key]) ? $options['entry_types_options'][$key] : [];

        $form->add('conditional', $type, $formOptions);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $prototypes = $form->getConfig()->getAttribute('prototypes');
        foreach ($prototypes as $key => $prototype) {
            $prototypes[$key] = $prototype->setParent($form)->createView($view);
        }

        $view->vars['prototypes'] = $prototypes;

        parent::buildView($view, $form, $options);
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $prototypes = $view->vars['prototypes'];
        $prototypesData = [];

        foreach ($prototypes as $key => $prototype) {
            $prototypesData[] = [
                'key' => $key,
                'form' => $this->vueForm->createData($prototype),
            ];
        }

        $data->set('prototypes', $prototypesData);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_types_options' => [],
            'prototype_name' => 'prototype_name',
            'component' => 'form-conditional',
            'component_model' => 'ConditionalForm',
        ]);

        $resolver->setRequired('entry_types');
        $resolver->setRequired('entry_type_resolver');
    }

    public function mapDataToForms($viewData, \Traversable $forms)
    {
        if (null === $viewData) {
            return;
        }

        $forms = iterator_to_array($forms);

        /** @var Form $conditionalForm */
        $conditionalForm = $forms['conditional'];

        if ($this->keyChanged) {
            // if key changed we let the form itself create a new data object by setting its data to null
            $conditionalForm->setData(null);
            $this->keyChanged = false;
            return;
        }

        $conditionalForm->setData($viewData);
    }

    public function mapFormsToData(\Traversable $forms, &$viewData)
    {
        $forms = iterator_to_array($forms);
        $viewData = $forms['conditional']->getData();
    }
}
