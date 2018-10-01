<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;


use Enhavo\Bundle\GridBundle\Model\Column\OneColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('width', ChoiceType::class, [
            'label' => 'column.label.width',
            'translation_domain' => 'EnhavoGridBundle',
            'choices' => [
                OneColumnItem::WIDTH_FULL => 'column.label.width.full',
                OneColumnItem::WIDTH_CONTAINER => 'column.label.width.container'
            ]
        ]);

        $builder->add('column', ColumnType::class, [
            'label' => 'column.label.column',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OneColumnItem::class
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_one_column_item';
    }
}
