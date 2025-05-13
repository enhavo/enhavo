<?php

namespace App\Form\Type;

use App\Entity\TableRow;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author blutze-media
 */
class TableRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('children', ListType::class, [
            'label' => 'Cells',
            'entry_type' => TableCellType::class,
            'sortable' => true,
        ]);
        $builder->add('position', PositionType::class, [
            'attr' => [
                'data-position' => 'data-position',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableRow::class,
        ]);
    }
}
