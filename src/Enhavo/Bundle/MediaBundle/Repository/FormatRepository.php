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
    public function findByFormatNameAndFile($formatName, FileInterface $file, $filterOperationsLockTimeout = FormatManager::LOCK_FILTER_OPERATIONS_TIMEOUT)
    {
        $queryBuilder = $this->createQueryBuilder('format');
        $queryBuilder
            ->andWhere('format.name = :formatName')
            ->andWhere('format.file = :file')
            ->andWhere('format.filterOperationsLock >= :lockTimeout')
            ->setParameter('formatName', $formatName)
            ->setParameter('file', $file)
            ->setParameter('lockTimeout', new \DateTime($filterOperationsLockTimeout . ' seconds ago'));

        $allResults = $queryBuilder->getQuery()->getResult();
        if (count($allResults) > 0) {
            return $allResults[0];
        }
        return null;
    }

    public function findByTimedOutFilterOperationsLock($filterOperationsLockTimeout = FormatManager::LOCK_FILTER_OPERATIONS_TIMEOUT)
    {
        $queryBuilder = $this->createQueryBuilder('format');
        $queryBuilder
            ->andWhere('format.filterOperationsLock < :lockTimeout')
            ->setParameter('lockTimeout', new \DateTime($filterOperationsLockTimeout . ' seconds ago'));

        return $queryBuilder->getQuery()->getResult();
    }
}
