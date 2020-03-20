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
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class FormatRepository extends EntityRepository
{
    public function findByFormatNameAndFile($formatName, FileInterface $file, $lockTimeout = FormatManager::LOCK_TIMEOUT)
    {
        $queryBuilder = $this->createQueryBuilder('format');
        $queryBuilder
            ->andWhere('format.name = :formatName')
            ->andWhere('format.file = :file')
            ->andWhere('format.lockAt >= :lockTimeout')
            ->setParameter('formatName', $formatName)
            ->setParameter('file', $file)
            ->setParameter('lockTimeout', new \DateTime($lockTimeout . ' seconds ago'));

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
