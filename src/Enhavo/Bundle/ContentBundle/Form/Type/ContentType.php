<?php

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Enhavo\Bundle\AppBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\AppBundle\Form\Type\SlugType;
use Enhavo\Bundle\ContentBundle\EventListener\RouterSubscriber;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouterType;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    /**
     * @var string
     */
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('meta_description', TextareaType::class, array(
            'label' => 'form.label.meta_description',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('page_title', TextType::class, array(
            'label' => 'form.label.page_title',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('public', BooleanType::class, array(
            'label' => 'form.label.public',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('priority', ChoiceType::class, array(
            'label' => 'form.label.priority',
            'translation_domain' => 'EnhavoContentBundle',
            'choices'   => array(
                '1' => '0.1',
                '2' => '0.2',
                '3' => '0.3',
                '4' => '0.4',
                '5' => '0.5',
                '6' => '0.6',
                '7' => '0.7',
                '8' => '0.8',
                '9' => '0.9',
                '10' => '1'
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('change_frequency', ChoiceType::class, array(
            'label' => 'form.label.change_frequency',
            'translation_domain' => 'EnhavoContentBundle',
            'choices'   => array(
                'form.label.always' => 'always',
                'form.label.hourly' => 'hourly',
                'form.label.daily' => 'daily',
                'form.label.weekly' => 'weekly',
                'form.label.monthly' => 'monthly',
                'form.label.yearly' => 'yearly',
                'form.label.never' => 'never',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('noIndex', BooleanType::class, array(
            'label' => 'form.label.no_index',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('noFollow', BooleanType::class, array(
            'label' => 'form.label.no_follow',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('publication_date', DateTimeType::class, array(
            'label' => 'form.label.publication_date',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('published_until', DateTimeType::class, array(
            'label' => 'form.label.published_until',
            'translation_domain' => 'EnhavoContentBundle'
        ));

        $builder->add('openGraphTitle', TextType::class, array(
            'label' => 'form.label.openGraphTitle',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('openGraphDescription', TextType::class, array(
            'label' => 'form.label.openGraphDescription',
            'translation_domain' => 'EnhavoContentBundle',
            'translation' => $this->translation
        ));

        $builder->add('openGraphImage', MediaType::class, array(
            'label' => 'form.label.openGraphImage',
            'translation_domain' => 'EnhavoContentBundle',
            'multiple' => false
        ));

        if($options['slugable']) {
            $builder->add('slug', SlugType::class, []);
        }

        if($options['routable']) {
            $builder->add('route', RouteType::class, []);
        }

        if($options['router']) {
            $builder->add('router_route', RouterType::class, []);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'slugable' => false,
            'routable' => false,
            'router' => false
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_content_content';
    }
}