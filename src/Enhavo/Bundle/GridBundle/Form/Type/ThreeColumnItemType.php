<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;


use Enhavo\Bundle\GridBundle\Entity\ThreeColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', ColumnType::class, [

        ]);

        $builder->add('columnTwo', ColumnType::class, [

        ]);

        $builder->add('columnThree', ColumnType::class, [

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ThreeColumnItem::class
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_three_column_item';
    }
}
