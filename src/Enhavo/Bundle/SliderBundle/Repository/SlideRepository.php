<?php
/**
 * SliderRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class SlideRepository extends EntityRepository
{
    public function findPublished($slider = null)
    {
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->andWhere('n.publication_date <= :currentDate');
        $query->setParameter('currentDate', new \DateTime());
        $query->orderBy('n.order', 'desc');
        return $query->getQuery()->getResult();
    }
}