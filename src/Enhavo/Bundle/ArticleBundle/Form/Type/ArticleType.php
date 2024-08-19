<?php

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\CommentBundle\Form\Type\ThreadType;
use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', WysiwygType::class, array(
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('picture', MediaType::class, array(
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
            'formats' => [
                'articleThumbnail' => 'Article Thumbnail'
            ]
        ));

//        $builder->add('content', BlockNodeType::class, array(
//            'label' => 'form.label.content',
//            'translation_domain' => 'EnhavoAppBundle',
//        ));

        $builder->add('thread', ThreadType::class, array(
            'label' => 'form.label.comment',
            'translation_domain' => 'EnhavoAppBundle',
        ));

        $builder->add('categories', TermTreeChoiceType::class, [
            'multiple' => true,
            'taxonomy' => 'article_category'
        ]);

        $builder->add('tags', TermAutoCompleteChoiceType::class, [
            'multiple' => true,
            'route' => 'enhavo_article_tag_auto_complete',
            'translation_domain' => 'EnhavoArticleBundle',
            'create_route' => 'enhavo_article_tag_create',
            'edit_route' => 'enhavo_article_tag_update',
            'view_key' => 'article_tags'
        ]);
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
}
