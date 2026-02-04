<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Person;
use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoSuggestionEntityType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('birthday', DateType::class);
        $builder->add('occupation', TermAutoSuggestionEntityType::class, [
            'taxonomy' => 'occupation',
            'route' => 'app_term_occupation_auto_complete',
        ]);
        $builder->add('picture', MediaType::class, [
            'multiple' => false,
            'formats' => [
                'person_image' => 'Person Image',
            ],
        ]);
        $builder->add('category', TermTreeChoiceType::class, [
            'taxonomy' => 'app_category',
        ]);
        $builder->add('otherCategory', TermTreeChoiceType::class, [
            'expanded' => false,
            'taxonomy' => 'app_category',
        ]);
        $builder->add('amountChildren', NumberType::class, [
            'label' => 'Amount children',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
