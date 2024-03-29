<?php

namespace App\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoSuggestionEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('birthday', DateType::class);
        $builder->add('occupation', TermAutoSuggestionEntityType::class, [
            'taxonomy' => 'occupation',
            'route' => 'app_term_occupation_auto_complete'
        ]);
        $builder->add('picture', MediaType::class, [
            'multiple' => false,
            'formats' => [
                'person_image' => 'Person Image',
            ]
        ]);
    }
}
