<?php

namespace enhavo\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array());
        $builder->add('order', 'hidden', array('attr' => array('data-category-item-order' => '')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'enhavo\CategoryBundle\Entity\Category'
        ));
    }

    public function getName()
    {
        return 'enhavo_category_category';
    }
}
