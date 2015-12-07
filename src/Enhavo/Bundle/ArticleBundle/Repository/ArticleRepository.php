<?php
/**
 * ArticleRepository.php
 *
 * @since 27/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ArticleBundle\Entity\Article;

class ArticleRepository extends EntityRepository
{
    public function filterByDate($year = 0, $month = 0, $limit = 0)
    {
        $month = intval($month);
        $year = intval($year);
        $limit = intval($limit);

        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');

        if($year >= 1970 && $month > 0 && $month < 13) {
            $monthEnd = new \DateTime(sprintf('%s-%s-01 23:59:59', $year, $month));
            $query->Where('n.publication_date >= :monthStart');
            $query->AndWhere('n.publication_date <= :monthEnd');
            $query->setParameter('monthStart', new \DateTime(sprintf('%s-%s-01 00:00:00', $year, $month)));
            $query->setParameter('monthEnd', $monthEnd->modify('last day of this month'));
        } elseif($year >= 1970) {
            $query->Where('n.publication_date >= :yearStart');
            $query->AndWhere('n.publication_date <= :yearEnd');
            $query->setParameter('yearStart', new \DateTime(sprintf('%s-01-01 00:00:00', $year)));
            $query->setParameter('yearEnd', new \DateTime(sprintf('%s-12-31 23:59:59', $year)));
        } else {
            $query->orderBy('n.sticky','desc');
        }

        $query->andWhere('n.publication_date <= :currentDate');
        $query->setParameter('currentDate', new \DateTime());
        if($limit > 0) {
            $query->setMaxResults($limit);
        }

        $query->orderBy('n.publication_date','desc');
        return $query->getQuery()->getResult();
    }

    public function getMonths()
    {
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->setParameter('currentDate', new \DateTime());
        $query->orderBy('n.publication_date','desc');
        $article = $query->getQuery()->getResult();

        $tmpDates = array();

        /** @var $item Article */
        foreach($article as $item) {
            if($item->getPublicationDate()) {
                $tmpDates[] = $item->getPublicationDate()->format('Y-m');
            }
        }
        $tmpDates = array_unique($tmpDates);
        $dates = array();
        foreach($tmpDates as $date) {
            $dates[] = new \DateTime(sprintf('%s-01', $date));
        }

        return $dates;
    }

    public function findPublished()
    {
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->setParameter('currentDate', new \DateTime());
        $query->orderBy('n.publication_date','desc');
        $articles = $query->getQuery()->getResult();

        return $articles;
    }

    /**
     * Returns the next published article in order if sorted by publication date.
     * Only returns articles that are published and whose publication date does not lie in the future.
     * The order used is the same as in findPublished().
     *
     * @param Article $currentArticle The current article
     * @param \DateTime $currentDate The date used to determine if a publication date lies in the future. If null or omitted, today is used.
     * @return null|Article The next article after $currentArticle, or null if $currentArticle is the last one.
     */
    public function findNextInDateOrder(Article $currentArticle, \DateTime $currentDate = null)
    {
        if (null === $currentDate) {
            $currentDate = new \DateTime();
        }

        // Find articles with same date
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->andWhere('n.publication_date = :articleDate');
        $query->setParameter('currentDate', $currentDate);
        $query->setParameter('articleDate', $currentArticle->getPublicationDate());
        $articlesSameDate = $query->getQuery()->getResult();
        if (!empty($articlesSameDate) && !($articlesSameDate[0]->getId() == $currentArticle->getId())) {
            // Return previous in list
            for($i = 0; $i < count($articlesSameDate); $i++) {
                if ($articlesSameDate[$i]->getId() == $currentArticle->getId()) {
                    return $articlesSameDate[$i - 1];
                }
            }
        }

        // No next one at current date, search for newer ones
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->andWhere('n.publication_date > :articleDate');
        $query->setParameter('currentDate', $currentDate);
        $query->setParameter('articleDate', $currentArticle->getPublicationDate());
        $query->addOrderBy('n.publication_date','asc');
        $query->addOrderBy('n.id','desc');
        $query->setMaxResults(1);
        $nextArticle = $query->getQuery()->getResult();
        if (empty($nextArticle)) {
            // No newer articles
            return null;
        } else {
            return $nextArticle[0];
        }
    }

    /**
     * Returns the previous published article in order if sorted by publication date.
     * Only returns articles that are published and whose publication date does not lie in the future.
     * The order used is the same as in findPublished().
     *
     * @param Article $currentArticle The current article
     * @param \DateTime $currentDate The date used to determine if a publication date lies in the future. If null or omitted, today is used.
     * @return null|Article The previous article before $currentArticle, or null if $currentArticle is the first one.
     */
    public function findPreviousInDateOrder(Article $currentArticle, \DateTime $currentDate = null)
    {
        if (null === $currentDate) {
            $currentDate = new \DateTime();
        }

        // Find articles with same date
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->andWhere('n.publication_date = :articleDate');
        $query->setParameter('currentDate', $currentDate);
        $query->setParameter('articleDate', $currentArticle->getPublicationDate());
        $articlesSameDate = $query->getQuery()->getResult();
        if (!empty($articlesSameDate) && !($articlesSameDate[count($articlesSameDate) - 1]->getId() == $currentArticle->getId())) {
            // Return next in list
            for($i = 0; $i < count($articlesSameDate); $i++) {
                if ($articlesSameDate[$i]->getId() == $currentArticle->getId()) {
                    return $articlesSameDate[$i + 1];
                }
            }
        }

        // No next one at current date, search for newer ones
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->andWhere('n.publication_date < :articleDate');
        $query->setParameter('currentDate', $currentDate);
        $query->setParameter('articleDate', $currentArticle->getPublicationDate());
        $query->addOrderBy('n.publication_date','desc');
        $query->addOrderBy('n.id','asc');
        $query->setMaxResults(1);
        $nextArticle = $query->getQuery()->getResult();
        if (empty($nextArticle)) {
            // No older articles
            return null;
        } else {
            return $nextArticle[0];
        }
    }
}
