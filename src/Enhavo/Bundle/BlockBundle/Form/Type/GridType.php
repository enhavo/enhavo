<?php
/**
 * GridType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', ItemsType::class, [
            'entry_type' => ItemType::class,
            'item_groups' => $options['item_groups'],
            'items' => $options['items']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Grid::class,
            'label' => 'grid.label.grid',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => [],
            'items' => []
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid';
    }
}
