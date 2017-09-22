<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 10:59
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class MediaType extends AbstractType
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    public function __construct($formFactory, RepositoryInterface $repository)
    {
        $this->formFactory = $formFactory;
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->repository;
        $collection = new ArrayCollection();
        $formFactory = $this->formFactory;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($repository, &$collection, $options, $formFactory){
            $data = $event->getData();
            $form = $event->getForm();

            // Then add all rows again in the correct order
            foreach ($data as $name => $value) {
                $form->add($name, $options['entry_type'], array_replace(array(
                    'property_path' => '['.$name.']',
                ), $options['entry_options']));
            }
        });

        //convert view data into concrete file objects
        //save it to collection var because the normalization
        //will overwrite the model data immediately
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($repository, &$collection, $options, $formFactory) {
            $data = $event->getData();
            $form = $event->getForm();

            $normData = $form->getNormData();
            $viewData = $form->getViewData();

            foreach($data as $index => $formData) {
                $id = $formData['id'];

                $isNew = true;
                /** @var FileInterface $file */
                foreach($normData as $file) {
                    if($id == $file->getId()) {
                        $isNew = false;
                        break;
                    }
                }

                if($isNew) {
                    $file = $repository->find($id);
                    $form->add($index, $options['entry_type'], array_replace(array(
                        'property_path' => '['.$index.']',
                    ), $options['entry_options']));
                    $normData[] = $file;
                    $viewData[] = $file;
                }
            }
        });

        //after normalization write back to model data
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($collection, $options) {
                if ($options['multiple']) {
                    $event->setData($collection);
                } else {
                    $event->setData($collection->get(0));
                }
            }
        );

        if ($options['prototype']) {
            $prototypeOptions = array_replace(array(
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ), $options['entry_options']);

            if (null !== $options['prototype_data']) {
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $prototypeOptions);
            $builder->setAttribute('prototype', $prototype->getForm());
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototype')) {
            $prototype = $form->getConfig()->getAttribute('prototype');
            $view->vars['prototype'] = $prototype->setParent($form)->createView($view);
        }

        $view->vars['information'] = $options['information'];
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['item_template'] = $options['item_template'];
        $view->vars['mediaConfig'] = [
            'multiple' => $options['multiple'],
            'sortable' => $options['sortable'],
            'extensions' => []
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'entry_type' => FileType::class,
            'entry_options' => [],
            'multiple' => true,
            'sortable' => true,
            'information' => '',
            'allow_add' => true,
            'prototype' => true,
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'item_template' => 'EnhavoMediaBundle:Form:item.html.twig'
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_media';
    }

    public function getName()
    {
        return 'enhavo_media';
    }
}