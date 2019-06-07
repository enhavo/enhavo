<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.03.18
 * Time: 17:52
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
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

        $blocks = [];

        if(empty($options['blocks']) && empty($options['block_groups'])) {
            $blocks = $resolver->resolveDefaultBlocks();
        } else {
            if(count($options['blocks'])) {
                foreach($options['blocks'] as $block) {
                    $block = $resolver->resolveBlock($block);
                    if(!in_array($block, $blocks)) {
                        $blocks[] = $block;
                    }
                }
            }

            if(count($options['block_groups'])) {
                $blockGroup = $resolver->resolveBlockGroup($options['block_groups']);
                foreach($blockGroup as $block) {
                    if(!in_array($block, $blocks)) {
                        $blocks[] = $block;
                    }
                }
            }
        }

        $view->vars['blocks'] = $blocks;
        $view->vars['dynamic_config'] = [
            'route' => $options['block_route'],
            'prototypeName' => $options['prototype_name'],
            'collapsed' => $options['collapsed']
        ];
    }

    /**
     * @param array $options
     * @return ResolverInterface
     * @throws \Exception
     */
    private function getResolver(array $options)
    {
        $resolver = $options['block_resolver'];
        if(is_string($resolver)) {
            if(!$this->container->has($resolver)) {
                throw new \Exception(sprintf('Resolver "%s" for dynamic form not found', $resolver));
            }
            $resolver = $this->container->get($resolver);
        }

        if(!$resolver instanceof ResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form is not implements BlockResolverInterface', $resolver));
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'blocks' => [],
            'block_groups' => [],
            'block_resolver' => null,
            'block_route' => null,
            'block_class' => null,
            'prototype' => false,
            'entry_type' => DynamicBlockType::class,
            'collapsed' => false
        ]);

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
                'block_resolver' => $options['block_resolver'],
                'data_class' => $options['block_class']
            ], $value);
        });
    }
}