<?php
/**
 * EntityRepository.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Repository;

use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as SyliusEntityRepository;

class EntityRepository extends SyliusEntityRepository
{
    public function filter(FilterQuery $filterQuery)
    {
        $query = $this->createQueryBuilder('e');
        $i = 0;
        foreach($filterQuery->getWhere() as $where) {
            $i++;
            $query->andWhere(sprintf('e.%s %s :parameter%s', $where['property'], $this->getOperator($where), $i));
            $query->setParameter(sprintf('parameter%s', $i), $this->getValue($where));
        }

        return $this->getPaginator($query);
    }

    private function getValue($where)
    {
        $value = $where['value'];

        if($where['operator'] == FilterQuery::OPERATOR_LIKE) {
            return '%'.$value.'%';
        }

        return $value;
    }

    private function getOperator($where)
    {
        switch($where['operator']) {
            case(FilterQuery::OPERATOR_EQUALS):
                return '=';
            case(FilterQuery::OPERATOR_LIKE):
                return 'like';
        }
        throw new FilterException('Operator not supported in Repository');
    }
}