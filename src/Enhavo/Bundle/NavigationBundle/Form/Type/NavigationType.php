<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:10
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DynamicFormType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'navigation.label.name',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('code', TextType::class, [
            'label' => 'navigation.label.code',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('nodes', DynamicFormType::class, [
            'label' => 'navigation.label.items',
            'translation_domain' => 'EnhavoNavigationBundle',
            'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
            'item_route' => 'enhavo_navigation_navigation_form',
            'item_class' => Node::class,
            'items' => $options['items'],
            'item_groups' => $options['item_groups']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'items' => [],
           'item_groups' => []
        ]);
    }

    public function getName()
    {
        return 'enhavo_navigation_navigation';
    }
}