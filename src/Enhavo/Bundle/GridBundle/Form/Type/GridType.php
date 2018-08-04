<?php
/**
 * GridType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DynamicFormType;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Item\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', DynamicFormType::class, [
            'label' => 'grid.label.items',
            'translation_domain' => 'EnhavoGridBundle',
            'items' => null,
            'item_resolver' => 'enhavo_grid.resolver.item_resolver',
            'item_route' => 'enhavo_grid_item_form',
            'item_class' => Item::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Grid::class,
            'label' => 'grid.label.grid',
            'translation_domain' => 'EnhavoGridBundle',
        ));
    }

    public function getName()
    {
        return 'enhavo_grid';
    }
}
