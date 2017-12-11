<?php
/**
 * SliderRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Repository;

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
}
