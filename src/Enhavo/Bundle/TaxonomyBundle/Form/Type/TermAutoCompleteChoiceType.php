<?php

namespace Enhavo\Bundle\TaxonomyBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermAutoCompleteChoiceType extends AbstractType
{
    /*
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->dataClass,
            'choice_label' => 'name',
            'multiple' => true,
            'id_property' => 'id',
            'label_property' => 'name',
        ]);
    }

    public function getParent()
    {
        return AutoCompleteEntityType::class;
    }
}
