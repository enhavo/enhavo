<?php
/**
 * ReferenceRepository.php
 *
 * @since 07/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ReferenceBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ReferenceRepository extends EntityRepository
{
    public function filterByCategory($styleId, $sectorId, $numberPerPage = 0, $page = 0)
    {
        $query = $this->createQueryBuilder('r');

        $query->andWhere('r.public = true');

        if(intval($styleId) > 0) {
            $query->join('r.styles', 'st');
            $query->andWhere('st.id = ?1');
            $query->setParameter(1, $styleId);
        }

        if(intval($sectorId) > 0) {
            $query->join('r.sectors', 'se');
            $query->andWhere('se.id = ?2');
            $query->setParameter(2, $sectorId);
        }

        if($numberPerPage) {
            $query->setMaxResults($numberPerPage);
        }

        if($page && $numberPerPage) {
            $query->setFirstResult(($page-1)*$numberPerPage);
        }

        $query->orderBy('r.order','asc');


        return $query->getQuery()->getResult();
    }
}