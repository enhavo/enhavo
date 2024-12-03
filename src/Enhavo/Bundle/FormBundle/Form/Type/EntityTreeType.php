<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.10.18
 * Time: 23:45
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Helper\EntityTreeChoiceBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTreeType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $choices = $view->vars['choices'];
        $builder = new EntityTreeChoiceBuilder($options['parent_property']);
        $builder->build($choices);
        $view->vars['choice_tree_builder'] = $builder;
        $view->vars['children_container_class'] = $options['children_container_class'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $builder = $view->vars['choice_tree_builder'];
        if ($builder instanceof EntityTreeChoiceBuilder) {
            $builder->map($view);
        }
        if (!$options['expanded']) {
            $view->vars['choices'] = $builder->getChoiceViews();
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'parent_property' => 'parent',
            'children_container_class' => 'entity-tree-children',
            'count' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'entity_tree';
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
