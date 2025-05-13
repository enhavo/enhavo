<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\FormBundle\Form\Type\SlugType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouterType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('meta_description', TextareaType::class, [
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('page_title', TextType::class, [
            'label' => 'form.label.page_title',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('public', BooleanType::class, [
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('noIndex', BooleanType::class, [
            'label' => 'form.label.no_index',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('noFollow', BooleanType::class, [
            'label' => 'form.label.no_follow',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('canonicalUrl', TextType::class, [
            'label' => 'form.label.canonical_url',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('publication_date', DateTimeType::class, [
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('published_until', DateTimeType::class, [
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle',
            'allow_clear' => true,
        ]);

        $builder->add('openGraphTitle', TextType::class, [
            'label' => 'form.label.openGraphTitle',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('openGraphDescription', TextareaType::class, [
            'label' => 'form.label.openGraphDescription',
            'translation_domain' => 'EnhavoContentBundle',
        ]);

        $builder->add('openGraphImage', MediaType::class, [
            'label' => 'form.label.openGraphImage',
            'translation_domain' => 'EnhavoContentBundle',
            'multiple' => false,
        ]);

        if ($options['slugable']) {
            $builder->add('slug', SlugType::class, []);
        }

        if ($options['routable']) {
            $builder->add('route', RouteType::class, []);
        }

        if ($options['router']) {
            $builder->add('router_route', RouterType::class, []);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'slugable' => false,
            'routable' => false,
            'router' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_content_content';
    }
}
