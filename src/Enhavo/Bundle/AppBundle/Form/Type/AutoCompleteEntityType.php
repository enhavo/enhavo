<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteEntityType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager, PropertyAccessorInterface $propertyAccessor)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->entityManager->getRepository($options['class']);
        $propertyAccessor = $this->propertyAccessor;

        if($options['multiple'] === true) {
            $builder->addEventSubscriber(new MergeDoctrineCollectionListener());
            $builder->addModelTransformer(new CallbackTransformer(
                function ($originalDescription) use ($options, $propertyAccessor) {
                    $collection = new ArrayCollection();
                    if($originalDescription instanceof Collection) {
                        $collection = $originalDescription;
                    }
                    $data = [];
                    foreach($collection as $entry) {
                        if(!method_exists($entry, 'getId')) {
                            throw new \Exception('class need to be an entity with getId function');
                        }
                        $id = call_user_func([$entry, 'getId']);
                        if($options['choice_label'] instanceof \Closure) {
                            $label = $options['choice_label']($entry);
                        } elseif(is_string($options['choice_label'])) {
                            $label = $propertyAccessor->getValue($entry, $options['choice_label']);
                        } else {
                            $label = (string)$entry;
                        }
                        $data[] = [
                            'id' => $id,
                            'text' => $label
                        ];
                    }
                    return $data;
                },
                function ($submittedDescription) use ($repository) {
                    $collection = new ArrayCollection();
                    if(empty($submittedDescription)) {
                        return $collection;
                    }
                    $ids = explode(',', $submittedDescription);
                    foreach($ids as $id) {
                        $collection->add($repository->find($id));
                    }
                    return $collection;
                }
            ));
        } else {
            $builder->addModelTransformer(new CallbackTransformer(
                function ($originalDescription) use ($options, $propertyAccessor) {
                    if($originalDescription !== null) {
                        if(!method_exists($originalDescription, 'getId')) {
                            throw new \Exception('class need to be an entity with getId function');
                        }
                        $id = call_user_func([$originalDescription, 'getId']);
                        if($options['choice_label'] instanceof \Closure) {
                            $label = $options['choice_label']($originalDescription);
                        } elseif(is_string($options['choice_label'])) {
                            $label = $propertyAccessor->getValue($originalDescription, $options['choice_label']);
                        } else {
                            $label = (string)$originalDescription;
                        }
                        return [
                            'id' => $id,
                            'text' => $label
                        ];
                    }
                    return null;
                },
                function ($submittedDescription) use ($repository) {
                    if(empty($submittedDescription)) {
                        return null;
                    }
                    return $repository->find($submittedDescription);
                }
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['auto_complete_data'] = [
            'url' => $this->router->generate($options['route'], $options['route_parameters']),
            'route' => $options['route'],
            'route_parameters' => $options['route_parameters'],
            'value' => $view->vars['value'],
            'multiple' => $options['multiple'],
            'minimum_input_length' => $options['minimum_input_length']
        ];
        $view->vars['multiple'] = $options['multiple'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_label' => function ($object) {
                return (string)$object;
            },
            'route_parameters' => [],
            'compound' => false,
            'multiple' => false,
            'minimum_input_length' => 0
        ]);
        
        $resolver->setRequired([
            'route',
            'class',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'enhavo_auto_complete_entity';
    }
}
