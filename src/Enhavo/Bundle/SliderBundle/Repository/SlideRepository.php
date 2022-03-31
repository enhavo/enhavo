<?php
/**
 * SliderRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\SliderBundle\Entity\Slider;

class SlideRepository extends EntityRepository
{
    public function findPublished(Slider $slider = null)
    {
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publicationDate <= :currentDate');
        $query->andWhere('n.publishedUntil >= :currentDate OR n.publishedUntil IS NULL');
        $query->orderBy('n.position', 'desc');
        $query->setParameter('currentDate', new \DateTime());

        if ($slider) {
            $query->andWhere('n.slider = :slider');
            $query->setParameter('slider', $slider);
        }

        return $query->getQuery()->getResult();
    }

    public function findBySliderId($sliderId, ?FilterQuery $filterQuery = null)
    {
        $pagination = false;
        if ($filterQuery) {
            $query = $this->buildFilterQuery($filterQuery);
            $pagination = $filterQuery->isPaginated();
        } else {
            $query = $this->createQueryBuilder('a');
        }
        $query
            ->join('a.slider', 'sl')
            ->andWhere('sl.id = :id')
            ->setParameter('id', $sliderId)
            ->addOrderBy('a.position', 'ASC');

        if($pagination) {
            return $this->getPaginator($query);
        }
        return $query->getQuery()->getResult();
    }
}
