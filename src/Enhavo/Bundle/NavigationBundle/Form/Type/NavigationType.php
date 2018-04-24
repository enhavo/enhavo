<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:10
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DynamicFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class NavigationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('code', TextType::class, [
            'label' => 'form.label.code',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('nodes', DynamicFormType::class, [
            'label' => 'form.label.node',
            'translation_domain' => 'EnhavoNavigationBundle',
            'item_resolver' => 'enhavo_navigation.resolver.item_resolver',
            'item_route' => 'enhavo_navigation_navigation_form'
        ]);
    }

    public function getName()
    {
        return 'enhavo_navigation_navigation';
    }
}