<?php
/**
 * FormatRepository.php
 *
 * @since 03/09/17
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Repository;

use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class FormatRepository extends EntityRepository
{
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

    public function findByFormatNameAndFile($formatName, FileInterface $file)
    {
        $queryBuilder = $this->createQueryBuilder('format');
        $queryBuilder
            ->andWhere('format.name = :formatName')
            ->andWhere('format.file = :file')
            ->setParameter('formatName', $formatName)
            ->setParameter('file', $file);

        $allResults = $queryBuilder->getQuery()->getResult();
        if (count($allResults) > 0) {
            return $allResults[0];
        }
        return null;
    }

    public function findByTimedOutLock($lockTimeout = FormatManager::LOCK_TIMEOUT)
    {
        $queryBuilder = $this->createQueryBuilder('format');
        $queryBuilder
            ->andWhere('format.lockAt < :lockTimeout')
            ->setParameter('lockTimeout', new \DateTime($lockTimeout . ' seconds ago'));

        return $queryBuilder->getQuery()->getResult();
    }
}
