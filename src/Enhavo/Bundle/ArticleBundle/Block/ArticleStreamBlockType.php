<?php
/**
 * ArticleStreamType.php
 *
 * @since 04/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Block;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleStream;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleStreamFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleStreamType as ArticleStreamFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleStreamBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => ArticleStream::class,
            'parent' => ArticleStream::class,
            'form' => ArticleStreamFormType::class,
            'factory' => ArticleStreamFactory::class,
            'repository' => 'EnhavoArticleBundle:ArticleStream',
            'template' => 'EnhavoArticleBundle:Theme/Block:article-stream.html.twig',
            'label' => 'article.label.article_stream',
            'translationDomain' => 'EnhavoArticleBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'article_stream';
    }
}
