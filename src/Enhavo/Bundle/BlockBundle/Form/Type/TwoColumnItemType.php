<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;


use Enhavo\Bundle\GridBundle\Model\Column\TwoColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', GridType::class, [
            'label' => 'column.label.column_one',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);

        $builder->add('columnTwo', GridType::class, [
            'label' => 'column.label.column_two',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TwoColumnItem::class
        ));
    }

    public function getParent()
    {
        return ColumnType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_two_column_item';
    }
}
