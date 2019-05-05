<?php

namespace Enhavo\Bundle\TaxonomyBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermChoiceType extends AbstractType
{
    /*
     * @var string
     */
    protected $dataClass;


    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => true,
            'multiple' => true,
            'class' => $this->dataClass,
            'translation_domain' => 'EnhavoTaxonomyBundle'
        ));
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
