<?php
/**
 * FilterQuery.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\AppBundle\Exception\FilterException;

class FilterQuery
{
    const OPERATOR_EQUALS = '=';
    const OPERATOR_GREATER = '>';
    const OPERATOR_LESS = '<';
    const OPERATOR_GREATER_EQUAL = '>=';
    const OPERATOR_LESS_EQUAL = '<=';
    const OPERATOR_NOT = '!=';
    const OPERATOR_LIKE = 'like';
    const OPERATOR_START_LIKE = 'start_like';
    const OPERATOR_END_LIKE = 'end_like';

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    const HYDRATE_OBJECT = 'object';
    const HYDRATE_ID = 'id';

    /**
     * @var array
     */
    private $where = [];

    /**
     * @var array
     */
    private $orderBy = [];

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $hydrate;

    public function __construct(EntityManagerInterface $em, $class, $alias = 'a')
    {
        if(strlen($alias) != 1) {
            throw new \InvalidArgumentException('Alias should be a single letter');
        }

        $this->alias = $alias;
        $this->queryBuilder = new QueryBuilder($em);
        $this->queryBuilder->select($this->getAlias());
        $this->queryBuilder->from($class, $this->getAlias());
    }

    public function addOrderBy($property, $order, $joinProperty = null)
    {
        $this->orderBy[] = [
            'property' => $property,
            'order' => $order,
            'joinProperty' => $joinProperty
        ];

        return $this;
    }

    public function removeOrderBy($property, $order)
    {
        if(!$property && !$order){
            return $this;
        }
        foreach ($this->orderBy as $index => $orderBy){
            if($property && $orderBy['property'] !== $property){
                continue;
            }
            if($order && $orderBy['operator'] !== $order){
                continue;
            }
            unset($this->orderBy[$index]);
        }

        return $this;
    }

    public function addWhere($property, $operator, $value, $joinProperty = null)
    {
        $this->where[] = [
            'property' => $property,
            'operator' => $operator,
            'value' => $value,
            'joinProperty' => $joinProperty
        ];

        return $this;
    }

    public function removeWhere($property, $operator, $value, $joinProperty = null)
    {
        if(!$property && !$operator && !$value && !$joinProperty){
            return $this;
        }
        foreach ($this->where as $index => $where){
            if($property && $where['property'] !== $property){
                continue;
            }
            if($operator && $where['operator'] !== $operator){
                continue;
            }
            if($value && $where['value'] !== $value){
                continue;
            }
            if($joinProperty && $where['joinProperty'] !== $joinProperty){
                continue;
            }
            unset($this->where[$index]);
        }

        return $this;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function build()
    {
        /** @var QueryBuilder $query */
        $query = $this->queryBuilder;
        $i = 0;
        foreach($this->getWhere() as $index => $where) {
            $i++;
            if($where['joinProperty']) {
                if(is_array($where['joinProperty']) && count($where['joinProperty'])) {
                    $joinPrefixes = $this->createJoinPropertyArray($index, count($where['joinProperty']) + 1);
                    foreach($where['joinProperty'] as $joinProperty) {
                        $joinPrefix = array_shift($joinPrefixes);
                        $query->join(sprintf('%s.%s', $joinPrefix, $joinProperty), $joinPrefixes[0]);
                    }
                    $query->andWhere(sprintf('%s.%s %s %s', $joinPrefixes[0], $where['property'], $this->getOperator($where), $this->getParameter($where, $i)));
                } else {
                    $query->join(sprintf('%s.%s', $this->getAlias(), $where['property']), sprintf('j%s', $i));
                    $query->andWhere(sprintf('j%s.%s %s %s', $i, $where['property'], $this->getOperator($where), $this->getParameter($where, $i)));
                }
            } else {
                $query->andWhere(sprintf('%s.%s %s %s', $this->getAlias(), $where['property'], $this->getOperator($where), $this->getParameter($where, $i)));
            }

            if($this->hasParameter($where)) {
                $query->setParameter(sprintf('parameter%s', $i), $this->getValue($where));
            }
        }

        $indexMin = count($this->getWhere());
        foreach($this->getOrderBy() as $index => $order) {
            $i++;
            if($order['joinProperty']) {
                if(is_array($order['joinProperty']) && count($order['joinProperty'])) {
                    $joinPrefixes = $this->createJoinPropertyArray($indexMin + $index, count($order['joinProperty']) + 1);
                    foreach($order['joinProperty'] as $joinProperty) {
                        $joinPrefix = array_shift($joinPrefixes);
                        $query->leftJoin(sprintf('%s.%s', $joinPrefix, $joinProperty), $joinPrefixes[0]);
                    }
                    $query->addOrderBy(sprintf('%s.%s', $joinPrefixes[0], $order['property']), $order['order']);
                } else {
                    $query->leftJoin(sprintf('%s.%s', $this->getAlias(), $order['property']), sprintf('j%s', $i));
                    $query->addOrderBy(sprintf('j%s.%s', $i, $order['property']), $order['order']);
                }
            } else {
                $query->addOrderBy(sprintf('%s.%s', $this->getAlias(), $order['property']), $order['order']);
            }
        }

        if ($this->getHydrate() === self::HYDRATE_ID) {
            $query->select($this->getAlias() . '.id');
        }

        return $this;
    }

    public function createJoinPropertyArray($index, $length)
    {
        $i=0;
        $allLetters = [];

        $letters = range($this->getAlias(), 'z');
        $lettersAdded = $letters;
        while ($length > 0) {
            $i++;
            foreach ($lettersAdded as $indexFor => &$addLetter){
                $addLetter .= $letters[$indexFor];
            }
            foreach ($lettersAdded as $letter) {
                $letter .= $index;
                $allLetters[] = $letter;
                $length--;
                if($length <= 0){
                    $allLetters[0] = $this->getAlias();
                    return $allLetters;
                }
            }
        }
        return $allLetters;
    }

    private function getValue($where)
    {
        $value = $where['value'];

        if($where['operator'] == FilterQuery::OPERATOR_LIKE) {
            return '%'.$value.'%';
        }

        if($where['operator'] == FilterQuery::OPERATOR_START_LIKE) {
            return $value.'%';
        }

        if($where['operator'] == FilterQuery::OPERATOR_END_LIKE) {
            return '%'.$value;
        }

        return $value;
    }

    private function getOperator($where)
    {
        $value = $where['value'];

        switch($where['operator']) {
            case(FilterQuery::OPERATOR_EQUALS):
                if($value === null) {
                    return 'is';
                }
                return '=';
            case(FilterQuery::OPERATOR_GREATER):
                return '>';
            case(FilterQuery::OPERATOR_GREATER_EQUAL):
                return '>=';
            case(FilterQuery::OPERATOR_LESS):
                return '<';
            case(FilterQuery::OPERATOR_LESS_EQUAL):
                return '<=';
            case(FilterQuery::OPERATOR_NOT):
                if($value === null) {
                    return 'is not';
                }
                return '!=';
            case(FilterQuery::OPERATOR_LIKE):
            case(FilterQuery::OPERATOR_START_LIKE):
            case(FilterQuery::OPERATOR_END_LIKE):
                return 'like';
        }
        throw new FilterException('Operator not supported in Repository');
    }

    private function getParameter($where, $number)
    {
        $value = $where['value'];
        if(FilterQuery::OPERATOR_EQUALS && $value === null) {
            return 'null';
        }
        return sprintf(':parameter%s', $number);
    }

    private function hasParameter($where)
    {
        $value = $where['value'];
        if(FilterQuery::OPERATOR_EQUALS && $value === null) {
            return false;
        }
        return true;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getHydrate(): string
    {
        return $this->hydrate;
    }

    /**
     * @param string $hydrate
     */
    public function setHydrate(string $hydrate): void
    {
        $this->hydrate = $hydrate;
    }
}
