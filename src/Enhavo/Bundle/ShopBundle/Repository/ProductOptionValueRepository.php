<?php

namespace Enhavo\Bundle\ShopBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ProductOptionValueRepository extends EntityRepository
{
    public function findByValue(string $value, string $locale): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.value = :value')
            ->andWhere('translation.locale = :locale')
            ->setParameter('value', $value)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getResult()
            ;
    }
}
