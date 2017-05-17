<?php
/**
 * ArtikelNumberStatistic.php
 *
 * @since 17/05/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Statistic;


use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\DashboardBundle\Statistic\StatisticProviderInterface;

class ArticleStatistic extends AbstractType implements StatisticProviderInterface
{
    public function getLabel()
    {
        return $this->container->get('translator')->trans('article.label.article', [], 'EnhavoArticleBundle');
    }

    public function getNumber()
    {
        return count($this->container->get('enhavo_article.repository.article')->findPublished());
    }

    public function getIcon()
    {
        return 'open-book';
    }

    public function getType()
    {
        return 'article';
    }
}