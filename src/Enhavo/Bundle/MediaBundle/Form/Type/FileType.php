<?php

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
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
        private readonly FormFactory $formFactory,
        private readonly EntityRepository $repository,
        private readonly NormalizerInterface $serializer,
        private readonly array $formConfigurations,
        private readonly string $dataClass,
        private readonly ActionManager $actionManager,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $submitData = null;

        $type = get_class($builder->getType()->getInnerType());

        $transformer = new CallbackTransformer(
            function ($originalDescription) {
                return $originalDescription;
            },
            function ($submittedDescription) use (&$submitData, $options, $type) {
                if($submittedDescription instanceof FileInterface && $submittedDescription->getId() === null) {
                    $file = $this->repository->find($submitData['id']);

                    $form = $this->formFactory->create($type, $file, $options);
                    $form->submit($submitData);
                    /** @var FileInterface $file */
                    $file = $form->getData();
                    $file->setGarbage(false);
                    $file->setGarbageTimestamp(null);

                    return $file;
                }
                return $submittedDescription;
            }
        );

        $builder->addModelTransformer($transformer);
        $builder->addViewTransformer($transformer);

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

        $parameterType = $options['parameters_type'] ?? $this->formConfigurations[$options['config']]['parameters_type'];
        $parameterOptions = $options['parameters_options'] ?? $this->formConfigurations[$options['config']]['parameters_options'];
        if ($parameterType) {
            $builder->add('parameters', $parameterType, $parameterOptions);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $vueData = $view->vars['vue_data'];
        if ($vueData) {
            $vueData['file'] = $this->serializer->normalize($form->getData(), null, ['groups' => ['media', 'media_private']]);
            if (isset($options['component_model'])) {
                $vueData['componentModel'] = $options['component_model'];
            } else if (!isset($vueData['componentModel'])) {
                $vueData['componentModel'] = 'MediaItemForm';
            }

            $vueData['actions'] = $this->getActions($options, $form);
            $vueData['formats'] = $options['formats'];
        }
    }

    private function getActions($options, FormInterface $form): array
    {
        $configuration = $options['actions_file'] ?? $this->formConfigurations[$options['config']]['actions_file'];
        $actions = $this->actionManager->getActions($configuration);

        $viewData = [];
        foreach ($actions as $action) {
            $viewData[] = $action->createViewData();
        }
        return $viewData;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'parameters_type' => null,
            'parameters_options' => null,
            'data_class' => $this->dataClass,
            'formats' => [],
            'config' => 'default',
            'actions' => null,
        ]);

        $resolver->setAllowedValues('config', array_keys($this->formConfigurations));
    }
}
