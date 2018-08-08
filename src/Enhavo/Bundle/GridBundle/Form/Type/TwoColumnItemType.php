<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;


use Enhavo\Bundle\GridBundle\Entity\TwoColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', ColumnType::class, []);

        $builder->add('columnTwo', ColumnType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TwoColumnItem::class
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_two_column_item';
    }
}
