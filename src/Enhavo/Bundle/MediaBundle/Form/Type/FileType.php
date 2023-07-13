<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.09.17
 * Time: 14:37
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Media\ExtensionManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileType extends AbstractType
{
    public function __construct(
        private FormFactory $formFactory,
        private RepositoryInterface$repository,
        private ExtensionManager$extensionManager,
        private NormalizerInterface $serializer,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submitData = null;
        $repository = $this->repository;
        $formFactory = $this->formFactory;

        $type = get_class($builder->getType()->getInnerType());

        $builder->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) {
                return $originalDescription;
            },
            function ($submittedDescription) use (&$submitData, $repository, $formFactory, $options, $type) {
                if($submittedDescription instanceof FileInterface && $submittedDescription->getId() === null) {
                    $file = $repository->find($submitData['id']);

                    /** @var FormFactory $formFactory */
                    $form = $formFactory->create($type, $file, $options);
                    $form->submit($submitData);
                    /** @var FileInterface $file */
                    $file = $form->getData();
                    $file->setGarbage(false);
                    $file->setGarbageTimestamp(null);

                    return $file;
                }
                return $submittedDescription;
            }
        ));

        $builder->addViewTransformer(new CallbackTransformer(
            function ($originalDescription) {
                return $originalDescription;
            },
            function ($submittedDescription) use (&$submitData, $repository, $formFactory, $options, $type) {
                if($submittedDescription instanceof FileInterface && $submittedDescription->getId() === null) {
                    $file = $repository->find($submitData['id']);

                    /** @var FormFactory $formFactory */
                    $form = $formFactory->create($type, $file, $options);
                    $form->submit($submitData);
                    /** @var FileInterface $file */
                    $file = $form->getData();
                    $file->setGarbage(false);
                    $file->setGarbageTimestamp(null);

                    return $file;
                }
                return $submittedDescription;
            }
        ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use (&$submitData)
        {
            $data = $event->getData();
            $submitData = $data;
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event)
        {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('id', HiddenType::class, [
                'required' => true,
                'attr' => [
                    'data-media-item-id' => $data instanceof FileInterface ? $data->getId() : true
                ],
                'mapped' => false,
            ]);
        });

        $builder->add('filename', TextType::class, [
            'required' => true,
            'attr' => ['data-media-item-filename' => true],
        ]);

        $builder->add('order', PositionType::class, [
            'required' => true
        ]);

        if($options['parameters_type']) {
            $builder->add('parameters', $options['parameters_type'], $options['parameters_options']);
        }

        $this->extensionManager->buildForm($builder, $options);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $vueData = $view->vars['vue_data'];
        if ($vueData) {
            $vueData['file'] = $this->serializer->normalize($form->getData(), null, ['groups' => ['media', 'media_private']]);
            if (isset($options['component_model'])) {
                $vueData['componentModel'] = $options['component_model'];
            } else if (!isset($vueData['componentModel'])) {
                $vueData['componentModel'] = 'MediaItemForm';
            }
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children['parameters'] as $child) {
            $child->vars['attr'] = $child->vars['attr'] ?: [];
            $child->vars['attr']['data-parameter-key'] = $child->vars['name'];
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'parameters_type' => FileParametersType::class,
            'parameters_options' => [],
            'data_class' => File::class,
            'extensions' => []
        ]);
    }
}
