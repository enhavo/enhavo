<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('parent', TermTreeChoiceType::class, [
            'class' => Term::class,
            'label' => 'form.label.parent',
            'translation_domain' => 'EnhavoAppBundle',
            'placeholder' => '---',
            'taxonomy' => 'article_category',
            'expanded' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
