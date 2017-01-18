<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class ArticleMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'open-book');
        $this->setOption('label', $options, 'article.label.article');
        $this->setOption('translationDomain', $options, 'EnhavoArticleBundle');
        $this->setOption('route', $options, 'enhavo_article_article_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_ARTICLE_ARTICLE_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'article';
    }
}