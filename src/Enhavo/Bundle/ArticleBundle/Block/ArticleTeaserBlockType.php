<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Block;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleTeaserBlock;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleTeaserBlockFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleTeaserBlockType as ArticleTeaserBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleTeaserBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ArticleTeaserBlock::class,
            'form' => ArticleTeaserBlockFormType::class,
            'factory' => ArticleTeaserBlockFactory::class,
            'template' => 'theme/block/article-teaser.html.twig',
            'label' => 'article.label.article_teaser',
            'translation_domain' => 'EnhavoArticleBundle',
            'groups' => ['default', 'content'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_teaser';
    }
}
