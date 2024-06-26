<?php
/**
 * FileRepository.php
 *
 * @since 17/12/15
 * @author Fabian Liebl <fl@weareindeed.com>
 */

namespace Enhavo\Bundle\MediaBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class FileRepository extends EntityRepository
{
    public function findGarbage(\DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        $now->modify('-2 days');

        $query = $this->createQueryBuilder('f')
            ->select('f')
            ->where('f.garbage = true')
            ->andWhere('f.garbageTimestamp < :date')
            ->setParameter('date', $now)
            ->getQuery();

        return $query->getResult();
    }
}
