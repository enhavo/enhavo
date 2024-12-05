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

    public function countByChecksum(string $checksum): int
    {
        $query = $this->createQueryBuilder('f')
            ->select('count(f.id) as count')
            ->where('f.checksum = :checksum')
            ->setParameter('checksum', $checksum)
            ->getQuery();

        $result = $query->getSingleScalarResult();
        return intval($result);
    }

    public function findFileBy(array $criteria)
    {
        $qb = $this->createQueryBuilder('f');

        $properties = ['id', 'token', 'filename', 'extension', 'checksum'];

        foreach ($properties as $property) {
            if (array_key_exists($property, $criteria)) {
                $qb
                    ->andWhere(sprintf('f.%s = :%s', $property, $property))
                    ->setParameter($property, $criteria[$property]);
            }
        }

        if (array_key_exists('shortChecksum', $criteria)) {
            if (!preg_match('/[0-9a-z]{8}/', $criteria['shortChecksum'])) {
                return null;
            }

            $qb
                ->andWhere('f.checksum LIKE :shortChecksum')
                ->setParameter('shortChecksum', $criteria['shortChecksum'] . '%');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
