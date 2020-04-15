<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
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
            $builder->addViewTransformer(new CallbackTransformer(
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
                function ($submittedDescription) use ($repository, $options, $propertyAccessor) {
                    $collection = new ArrayCollection();
                    if(empty($submittedDescription)) {
                        return $collection;
                    }
                    $ids = explode(',', $submittedDescription);
                    $i = 0;
                    foreach($ids as $id) {
                        $i++;
                        $entity = $repository->find($id);
                        if($options['sortable']) {
                            $propertyAccessor->setValue($entity, $options['sort_property'], $i);
                        }
                        $collection->add($entity);
                    }
                    return $collection;
                }
            ));
        } else {
            $builder->addViewTransformer(new CallbackTransformer(
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
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['auto_complete_data'] = [
            'url' => $this->router->generate($options['route'], $options['route_parameters']),
            'route' => $options['route'],
            'route_parameters' => $options['route_parameters'],
            'value' => $view->vars['value'],
            'multiple' => $options['multiple'],
            'count' => $options['count'],
            'minimum_input_length' => $options['minimum_input_length'],
            'placeholder' => $options['placeholder'],
            'id_property' => $options['id_property'],
            'label_property' => $options['label_property'],
            'sortable' => $options['sortable'],
            'editable' => $options['editable'],
            'edit_route' => $options['edit_route']
        ];
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['count'] = $options['count'];
        $view->vars['create_route'] = $options['create_route'];
        $view->vars['create_button_label'] = $options['create_button_label'];

        if ($options['multiple'] === false) {
            $value = $view->vars['value'];
            if (is_object($value) && method_exists($value, 'getId')) {
                $view->vars['value'] = $value->getId();
            } elseif (is_array($value) && isset($value['id'])) {
                $view->vars['value'] = $value['id'];
            }
        }
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
            'minimum_input_length' => 0,
            'placeholder' => null,
            'create_route' => null,
            'create_button_label' => null,
            'id_property' => 'id',
            'label_property' => null,
            'sortable' => false,
            'count' => true,
            'sort_property' => null,
            'editable' => false,
            'edit_route' => null
        ]);

        $resolver->setRequired([
            'route',
            'class',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'enhavo_auto_complete_entity';
    }
}
