<?php

namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CategoryType extends AbstractType
{
    /**
     * @var $dataClass
     */
    protected $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array());
        $builder->add('text', 'wysiwyg', array());
        $builder->add('picture', 'enhavo_files', array(
            'label' => 'form.label.picture'
        ));
        $builder->add('slug', 'hidden', array(
            'label' => 'form.label.slug'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_category_category';
    }
}
