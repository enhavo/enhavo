<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;

/**
 * TranslationRouteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TranslationRouteRepository extends EntityRepository
{
    public function findTranslationRoute($class, $id, $property, $locale): ?TranslationRoute
    {
        $translationRoute = $this->createQueryBuilder('tr')
            ->join('tr.route', 'r')
            ->andWhere('r.contentClass = :class')
            ->andWhere('r.contentId = :id')
            ->andWhere('tr.locale = :locale')
            ->andWhere('tr.property = :property')
            ->setParameter('class', $class)
            ->setParameter('id', $id)
            ->setParameter('property', $property)
            ->setParameter('locale', $locale)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if ($translationRoute) {
            return $translationRoute[0];
        }

        return null;
    }

    /**
     * @return array|TranslationRoute[]
     */
    public function findTranslationRoutes($class, $id): array
    {
        $translationRoutes = $this->createQueryBuilder('tr')
            ->join('tr.route', 'r')
            ->andWhere('r.contentClass = :class')
            ->andWhere('r.contentId = :id')
            ->setParameter('class', $class)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $translationRoutes;
    }
}
