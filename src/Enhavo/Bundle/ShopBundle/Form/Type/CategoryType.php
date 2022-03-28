<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'form.label.name',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('parent', TermTreeChoiceType::class, array(
            'class' => Term::class,
            'label' => 'form.label.parent',
            'translation_domain' => 'EnhavoAppBundle',
            'placeholder' => '---',
            'taxonomy' => 'shop_category',
            'expanded' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
