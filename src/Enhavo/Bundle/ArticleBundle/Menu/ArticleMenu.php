<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class ArticleMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'open-book');
        $this->setDefaultOption('label', $options, 'article.label.article');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoArticleBundle');
        $this->setDefaultOption('route', $options, 'enhavo_article_article_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_ARTICLE_ARTICLE_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'article';
    }
}