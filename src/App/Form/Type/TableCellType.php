<?php
/**
 * @author blutze-media
 * @since 2024-11-06
 */

namespace App\Form\Type;

use App\Entity\TableCell;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableCellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('value', TextType::class);
        $builder->add('position', PositionType::class, [
            'attr' => [
                'data-position' => 'data-position',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableCell::class,
        ]);
    }
}
