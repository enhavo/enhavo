<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DynamicFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodesType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'navigation.label.items',
            'translation_domain' => 'EnhavoNavigationBundle',
            'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
            'item_route' => 'enhavo_navigation_navigation_form',
            'item_class' => $this->class
        ]);
    }

    public function getParent()
    {
        return DynamicFormType::class;
    }

    public function getName()
    {
        return 'enhavo_navigation_nodes';
    }
}


