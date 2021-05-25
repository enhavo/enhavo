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

    const UNIQUE_PARAMETER_PREFIX = 'qohbmrxo'; // This is a random sequence of 8 characters generated by a password generator.
                                                // It's added to join aliases and parameter names to avoid easily guessable names
                                                // that might accidentally be duplicated by someone in the callback function.

    /**
     * @var array
     */
    private $where = [];

    /**
     * @var FilterQueryOr[]
     */
    private $orBlocks = [];

    /**
     * @var array
     */
    private $orderBy = [];

    /**
     * @var array
     */
    private $callbacks = [];

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
        if ($joinProperty === null) {
            $joinProperty = [];
        } elseif (!is_array($joinProperty)) {
            $joinProperty = [ $joinProperty ];
        }
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
        if ($joinProperty === null) {
            $joinProperty = [];
        } elseif (!is_array($joinProperty)) {
            $joinProperty = [ $joinProperty ];
        }
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

    public function or(): FilterQueryOr
    {
        $this->orBlocks []= new FilterQueryOr();
        return $this->orBlocks[count($this->orBlocks) - 1];
    }

    public function addCallback(callable $callback, $additionalParameters = [])
    {
        $this->callbacks []= [
            'function' => $callback,
            'parameters' => $additionalParameters
        ];
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
        $globalIndex = 0;
        foreach($this->getWhere() as $where) {
            $globalIndex++;
            if(count($where['joinProperty'])) {
                $joinPrefix = $this->createJoins($query, $where['joinProperty'], $globalIndex);
                $query->andWhere(sprintf('%s.%s %s %s', $joinPrefix, $where['property'], $this->getOperator($where), $this->getParameter($where, $globalIndex)));
            } else {
                $query->andWhere(sprintf('%s.%s %s %s', $this->getAlias(), $where['property'], $this->getOperator($where), $this->getParameter($where, $globalIndex)));
            }
            if($this->hasParameter($where)) {
                $query->setParameter($this->getParameterName($globalIndex), $this->getValue($where));
            }
        }

        foreach($this->orBlocks as $orBlock) {
            $orQueryStrings = [];
            foreach($orBlock->getWhere() as $where) {
                $globalIndex++;
                if(count($where['joinProperty'])) {
                    $joinPrefix = $this->createJoins($query, $where['joinProperty'], $globalIndex);
                    $orQueryStrings []= sprintf('%s.%s %s %s', $joinPrefix, $where['property'], $this->getOperator($where), $this->getParameter($where, $globalIndex));
                } else {
                    $orQueryStrings []= sprintf('%s.%s %s %s', $this->getAlias(), $where['property'], $this->getOperator($where), $this->getParameter($where, $globalIndex));
                }
                if($this->hasParameter($where)) {
                    $query->setParameter($this->getParameterName($globalIndex), $this->getValue($where));
                }
            }
            $query->andWhere(sprintf('(%s)', implode(' OR ', $orQueryStrings)));
        }

        foreach($this->getOrderBy() as $order) {
            $globalIndex++;
            if(count($order['joinProperty'])) {
                $joinPrefix = $this->createJoins($query, $order['joinProperty'], $globalIndex);
                $query->addOrderBy(sprintf('%s.%s', $joinPrefix, $order['property']), $order['order']);
            } else {
                $query->addOrderBy(sprintf('%s.%s', $this->getAlias(), $order['property']), $order['order']);
            }
        }

        if ($this->getHydrate() === self::HYDRATE_ID) {
            $query->select($this->getAlias() . '.id');
        }

        foreach($this->callbacks as $callbackInfo) {
            $parameters = array_merge([$query, $this->getAlias()], $callbackInfo['parameters']);
            call_user_func_array($callbackInfo['function'], $parameters);
        }

        return $this;
    }

    private function createJoins(QueryBuilder $query, $joins, $index)
    {
        $joinPrefixes = $this->createJoinAliases($index, count($joins));
        foreach($joins as $joinProperty) {
            $joinPrefix = array_shift($joinPrefixes);
            $query->leftJoin(sprintf('%s.%s', $joinPrefix, $joinProperty), $joinPrefixes[0]);
        }
        return $joinPrefixes[0];
    }

    public function createJoinAliases($index, $length)
    {
        $result = [$this->getAlias()];
        for($i = 0; $i < $length; $i++) {
            $result []= 'j' . self::UNIQUE_PARAMETER_PREFIX . $index . '_' . $i;
        }
        return $result;
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
        return ':' . $this->getParameterName($number);
    }

    private function hasParameter($where)
    {
        $value = $where['value'];
        if(FilterQuery::OPERATOR_EQUALS && $value === null) {
            return false;
        }
        return true;
    }

    private function getParameterName($globalIndex)
    {
        return 'p' . self::UNIQUE_PARAMETER_PREFIX . $globalIndex;
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
