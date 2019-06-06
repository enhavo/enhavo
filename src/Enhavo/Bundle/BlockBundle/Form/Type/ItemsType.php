<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DynamicFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemsType extends AbstractType
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
            'label' => 'grid.label.items',
            'translation_domain' => 'EnhavoGridBundle',
            'item_resolver' => 'enhavo_grid.resolver.item_resolver',
            'item_route' => 'enhavo_grid_item_form',
            'item_class' => $this->class,
        ]);
    }

    public function getParent()
    {
        return DynamicFormType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_items';
    }
}


