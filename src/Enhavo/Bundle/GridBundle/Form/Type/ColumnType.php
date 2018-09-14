<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.08.18
 * Time: 11:09
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', ItemsType::class, [
            'entry_type' => ItemType::class,
            'items' => $options['items'],
            'item_groups' => $options['item_groups'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Grid::class,
            'items' => [],
            'item_groups' => []
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_column';
    }
}