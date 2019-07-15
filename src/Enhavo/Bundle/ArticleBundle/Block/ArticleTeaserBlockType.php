<?php

namespace Enhavo\Bundle\ArticleBundle\Block;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleTeaserBlock;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleTeaserBlockFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleTeaserBlockType as ArticleTeaserBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleTeaserBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => ArticleTeaserBlock::class,
            'parent' => ArticleTeaserBlock::class,
            'form' => ArticleTeaserBlockFormType::class,
            'factory' => ArticleTeaserBlockFactory::class,
            'repository' => 'ArticleTeaserBlock::class',
            'template' => 'theme/block/article-teaser.html.twig',
            'label' => 'article.label.article_teaser',
            'translationDomain' => 'EnhavoArticleBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'article_teaser';
    }
}
