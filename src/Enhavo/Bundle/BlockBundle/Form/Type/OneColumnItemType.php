<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Column\OneColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('column', GridType::class, [
            'label' => 'column.label.column',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OneColumnItem::class
        ]);
    }
    
    public function getParent()
    {
        return ColumnType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_one_column_item';
    }
}
