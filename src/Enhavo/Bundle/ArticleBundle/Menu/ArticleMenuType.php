<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'book',
            'label' => 'article.label.article',
            'translation_domain' => 'EnhavoArticleBundle',
            'route' => 'enhavo_article_admin_article_index',
            'permission' => 'ROLE_ENHAVO_ARTICLE_ARTICLE_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_article';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
