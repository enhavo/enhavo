<?php

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Enhavo\Bundle\CategoryBundle\Form\Type\CategoryEntityType;
use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\GridBundle\Form\Type\GridType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractResourceType
{
    /**
     * @var boolean
     */
    private $translation;

    public function __construct($dataClass, $validationGroups, $translation)
    {
        parent::__construct($dataClass, $validationGroups);
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', WysiwygType::class, array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
            'formats' => [
                'articleThumbnail' => 'Article Thumbnail'
            ]
        ));

        $builder->add('grid', GridType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('categories', CategoryEntityType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->dataClass,
            'slugable' => true,
            'validation_groups' => ['default']
        ));
    }

    public function getParent()
    {
        return ContentType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_article_article';
    }
}
