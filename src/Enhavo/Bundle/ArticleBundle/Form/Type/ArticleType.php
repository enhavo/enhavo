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

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\CommentBundle\Form\Type\ThreadType;
use Enhavo\Bundle\ContentBundle\Form\Type\ContentType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermTreeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teaser', WysiwygType::class, [
            'label' => 'form.label.teaser',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('picture', MediaType::class, [
            'label' => 'form.label.picture',
            'translation_domain' => 'EnhavoAppBundle',
            'multiple' => false,
            'formats' => [
                'articleThumbnail' => 'Article Thumbnail',
            ],
        ]);

        $builder->add('content', BlockNodeType::class, [
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('thread', ThreadType::class, [
            'label' => 'form.label.comment',
            'translation_domain' => 'EnhavoAppBundle',
        ]);

        $builder->add('categories', TermTreeChoiceType::class, [
            'multiple' => true,
            'taxonomy' => 'article_category',
        ]);

        $builder->add('tags', TermAutoCompleteChoiceType::class, [
            'multiple' => true,
            'route' => 'enhavo_article_admin_api_tag_auto_complete',
            'translation_domain' => 'EnhavoArticleBundle',
            'create_route' => 'enhavo_article_admin_tag_create',
            'edit_route' => 'enhavo_article_admin_tag_update',
            'frame_key' => 'article_tags',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'slugable' => true,
            'validation_groups' => ['default'],
        ]);
    }

    public function getParent()
    {
        return ContentType::class;
    }
}
