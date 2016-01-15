<?php
/**
 * FileRepository.php
 *
 * @since 17/12/15
 * @author Fabian Liebl <fabian.liebl@xq-web.de>
 */

namespace Enhavo\Bundle\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
