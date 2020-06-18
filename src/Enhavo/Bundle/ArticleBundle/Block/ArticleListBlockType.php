<?php
/**
 * ArticleListBlockType.php
 *
 * @since 04/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Block;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleListBlock;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleListFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleListBlockType as ArticleListFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleListBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ArticleListBlock::class,
            'form' => ArticleListFormType::class,
            'factory' => ArticleListFactory::class,
            'template' => 'theme/block/article-list.html.twig',
            'label' => 'article.label.article_list',
            'translation_domain' => 'EnhavoArticleBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_list';
    }
}
