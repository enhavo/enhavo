<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Form;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('url', TextType::class, [
            'label' => 'slide.form.label.url',
            'translation_domain' => 'EnhavoSliderBundle',
            'attr' => ['class' => 'link-type-external'],
        ]);

        $builder->add('text', TextareaType::class, [
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('publicationDate', DateType::class, [
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('publishedUntil', DateType::class, [
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle',
            'allow_clear' => true,
        ]);

        $builder->add('public', BooleanType::class, [
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('image', MediaType::class, [
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_slider_slide';
    }
}
