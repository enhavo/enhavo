<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;


use Enhavo\Bundle\GridBundle\Entity\OneColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('column', ColumnType::class, [
            'label' => 'grid.form.label.column',
            'translation_domain' => 'EnhavoGridBundle'
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
