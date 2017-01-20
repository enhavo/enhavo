<?php
/**
 * SliderRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SliderBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class SlideRepository extends EntityRepository
{
    public function findPublished($slider = null)
    {
        $query = $this->createQueryBuilder('n');
        $query->andWhere('n.public = true');
        $query->orderBy('n.position', 'desc');
        return $query->getQuery()->getResult();
    }
}