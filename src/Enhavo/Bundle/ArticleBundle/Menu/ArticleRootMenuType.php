<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\ListMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleRootMenuType extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'book',
            'label' => 'article.label.article',
            'translation_domain' => 'EnhavoArticleBundle',
            'children' => [
                'article' => [
                    'type' => 'article_article'
                ],
                'category' => [
                    'type' => 'article_category'
                ],
                'tag' => [
                    'type' => 'article_tag'
                ],
            ]
        ]);
    }

    public function getType()
    {
        return 'article';
    }
}
