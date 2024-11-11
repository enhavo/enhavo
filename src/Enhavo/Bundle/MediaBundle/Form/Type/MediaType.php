<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 10:59
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class MediaType extends AbstractType
{
    public function __construct(
        private readonly ActionManager $actionManager,
        private readonly array $formConfigurations,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            if ($options['multiple'] === false && $data instanceof FileInterface) {
                $event->setData([$data]);
            }
        }, 1);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            if ($options['multiple'] === false) {
                $data = $event->getData();
                if (is_array($data)) {
                    if(count($data)) {
                        $event->setData(array_pop($data));
                    } else {
                        $event->setData(null);
                    }
                }
            }
        }, 10);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['information'] = $options['information'];
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['edit'] = $options['edit'];
        $view->vars['sortable'] = $options['multiple']  ? $options['sortable'] : false;
        $view->vars['actions'] = $this->getActions($options);
        $view->vars['route'] = $options['route'] ?? $this->formConfigurations[$options['config']]['route'];
        $view->vars['upload'] = $options['upload'] ?? $this->formConfigurations[$options['config']]['upload'];
    }

    private function getActions($options): array
    {
        $configuration = $options['actions'] ?? $this->formConfigurations[$options['config']]['actions'];
        $actions = $this->actionManager->getActions($configuration);

        $viewData = [];
        foreach ($actions as $action) {
            $viewData[] = $action->createViewData();
        }
        return $viewData;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /*
         * If by_reference is set to false, the model data will be cloned. We need to avoid this behaviour in
         * order do keep doctrine references synchronized.
         */
        $resolver->setNormalizer('by_reference', function ($options, $value) {
            if ($options['multiple'] === false) {
                return true;
            }
            return $value;
        });

        $resolver->setNormalizer('entry_options', function ($options, $value) {
            if (!isset($value['config'])) {
                $value['config'] = $options['config'];
            }

            if (!isset($value['formats'])) {
                $value['formats'] = $options['formats'];
            }

            return $value;
        });

        $resolver->setDefaults(array(
            'entry_type' => FileType::class,
            'entry_options' => [],
            'edit' => true,
            'multiple' => true,
            'sortable' => true,
            'by_reference' => false,
            'information' => '',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'config' => 'default',
            'error_bubbling' => false,
            'route' => null,
            'actions' => null,
            'actions_file' => null,
            'upload' => null,
            'formats' => [],
        ));

        $resolver->setAllowedValues('config', array_keys($this->formConfigurations));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_media';
    }

    public function getParent()
    {
        return ListType::class;
    }
}
