<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.03.18
 * Time: 17:52
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicFormType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $resolver = $this->getResolver($options);

        if(count($options['items'])) {
            $item = $resolver->resolveItem($options['items']);
            $items = [$item];
        } elseif(!empty($options['item_group'])) {
            $items = $resolver->resolveItemGroup($options['item_group']);
        } else {
            $items = $resolver->resolveDefaultItems();
        }

        $view->vars['items'] = $items;
        $view->vars['dynamic_config'] = [
            'route' => $options['item_route'],
            'prototypeName' => $options['prototype_name']
        ];
    }

    /**
     * @param array $options
     * @return ResolverInterface
     * @throws \Exception
     */
    private function getResolver(array $options)
    {
        $resolver = $options['item_resolver'];
        if(is_string($resolver)) {
            if(!$this->container->has($resolver)) {
                throw new \Exception(sprintf('Resolver "%s" for dynamic form not found', $resolver));
            }
            $resolver = $this->container->get($resolver);
        }

        if(!$resolver instanceof ResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form is not implements ItemResolverInterface', $resolver));
        }

        return $resolver;
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_dynamic_form';
    }

    public function getName()
    {
        return 'enhavo_dynamic_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'enhavo_app_dynamic_item',
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'items' => [],
            'item_group' => null,
            'item_resolver' => null,
            'item_route' => null,
            'item_class' => null,
            'prototype' => false,
            'entry_type' => DynamicItemType::class
        ]);

        // force to create a unique placeholder for each form type
        $resolver->setNormalizer('prototype_name', function($options, $value) {
            if($value == '__name__') {
                return sprintf('__%s__', uniqid());
            }
            return $value;
        });


        // force to create a unique placeholder for each form type
        $resolver->setNormalizer('prototype_name', function($options, $value) {
            if($value == '__name__') {
                return sprintf('__%s__', uniqid());
            }
            return $value;
        });

        $resolver->setNormalizer('entry_options', function($options, $value) {
            if(!is_array($value)) {
                $value = [];
            }
            return array_merge([
                'item_resolver' => $options['item_resolver'],
                'data_class' => $options['item_class']
            ], $value);
        });
    }
}