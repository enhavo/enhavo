<?php
/**
 * VoucherRepository.php
 *
 * @since 29/10/18
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\ShopBundle\Model\VoucherInterface;

class VoucherRepository extends EntityRepository
{
    public function findValidByCode(string $code, \DateTime $currentDate = null): ?VoucherInterface
    {
        if ($currentDate === null) {
            $currentDate = new \DateTime();
        }

        $qb = $this->createQueryBuilder('v');
        $qb->where('v.enabled = true');
        $qb->andWhere('(v.expiredAt IS NULL OR v.expiredAt > :currentDate)');
        $qb->andWhere('v.code = :code');
        $qb->setParameter('currentDate', $currentDate);
        $qb->setParameter('code', $code);
        $result = $qb->getQuery()->getResult();
        if (count($result)) {
            return $result[0];
        }
        return null;
    }
}
