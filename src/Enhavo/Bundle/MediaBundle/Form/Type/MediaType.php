<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 10:59
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Enhavo\Bundle\MediaBundle\Media\ExtensionManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class MediaType extends AbstractType
{
    /**
     * @var ExtensionManager
     */
    private $extensionManager;

    /**
     * @var array
     */
    private $formConfiguration;

    /**
     * MediaType constructor.
     *
     * @param ExtensionManager $extensionManager
     * @param array $formConfiguration
     */
    public function __construct(ExtensionManager $extensionManager, array $formConfiguration)
    {
        $this->extensionManager = $extensionManager;
        $this->formConfiguration = $formConfiguration;
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
        $view->vars['item_template'] = $options['item_template'];
        $view->vars['upload'] = $options['upload'];
        $view->vars['extensionManager'] = $this->extensionManager;
        $view->vars['extensions'] = $options['extensions'];
        $view->vars['route'] = $options['route'];
        $view->vars['mediaConfig'] = [
            'multiple' => $options['multiple'],
            'sortable' =>  $options['multiple'] ? $options['sortable'] : false,
            'extensions' => $view->vars['extensions'],
            'upload' => $options['upload'],
            'edit' => $options['edit'],
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*
         * If by_reference is set to false, the model data will be cloned. We need to avoid this behaviour in
         * order do keep doctrine references synchronized.
         */
        $resolver->setNormalizer('by_reference', function ($options, $value) {
            if($options['multiple'] === false) {
                return true;
            }
            return $value;
        });

        $resolver->setNormalizer('entry_options', function ($options, $value) {
            if(isset($options['extensions']) && is_array($options['extensions'])) {
                if(is_array($value)) {
                    return array_merge($value, [
                        'extensions' => $options['extensions']
                    ]);
                }
                return [
                    'extensions' => $options['extensions']
                ];
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
            'item_template' => '@EnhavoMedia/admin/form/media/item.html.twig',
            'upload' => $this->formConfiguration['default_upload_enabled'],
            'extensions' => [],
            'route' => 'enhavo_media_upload',
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_media';
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}
