<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\ListMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleRootMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
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

    public static function getName(): ?string
    {
        return 'article';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
