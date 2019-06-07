<?php
/**
 * ArticleStreamType.php
 *
 * @since 04/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Container;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleStream;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleStreamFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleStreamType as ArticleStreamFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleStreamType extends AbstractConfiguration
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
            'template' => 'EnhavoArticleBundle:Theme/Container:article-stream.html.twig',
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
