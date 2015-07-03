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
        $news = $query->getQuery()->getResult();

        $tmpDates = array();

        /** @var $item Article */
        foreach($news as $item) {
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
}
