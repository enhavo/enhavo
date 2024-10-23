<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch\SearchFilter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\BetweenQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\MatchQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\QueryInterface;
use Enhavo\Bundle\SearchBundle\Model\Database\DataSet;
use Enhavo\Bundle\SearchBundle\Model\Database\Filter;
use Enhavo\Bundle\SearchBundle\Model\Database\Total;

class IndexRepository extends EntityRepository
{
    public function getSumScoreOfWord($word)
    {
        $query = $this->createQueryBuilder('s');
        $query->setParameter('word', $word);
        $query->select('sum(s.score)');
        $query->andWhere('s.word = :word');
        return $query->getQuery()->getSingleResult();
    }

    /**
     * @param SearchFilter $filter
     * @return array
     */
    public function getSearchResults(SearchFilter $filter)
    {
        $query = $this->createSearchQuery($filter);
        return $query->getQuery()->getResult();
    }

    /**
     * @param SearchFilter $filter
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQuery(SearchFilter $filter)
    {
        $query = $this->createQueryBuilder('i');
        $query->select(
            'd.contentId AS id',
            'd.contentClass AS class',
            'sum(i.score * t.count) AS calculated_score',
            'min(f.value) AS value',
            'min(f.key) AS key',
            'd.id AS dataset_id'
        );

        $query->innerJoin(Total::class, 't', 'WITH', 'i.word = t.word');
        $query->innerJoin(DataSet::class, 'd', 'WITH', 'i.dataSet = d');
        $query->leftJoin('d.filters', 'f');

        $query->groupBy('d.id');

        $i = 0;
        foreach($filter->getWords() as  $word) {
            $query->orWhere('i.word = :word_'.$i);
            $query->setParameter('word_'.$i, $word);
            $i++;
        }

        if($filter->getQueries()) {
            $i = 0;
            foreach($filter->getQueries() as $key => $filerQuery) {
                $ids = $this->createFilterIds($key, $filerQuery);
                $query->andWhere(sprintf('d.id IN (:filterIds_%s)', $i));
                $query->setParameter(sprintf('filterIds_%s', $i), $ids);
                $i++;
            }
        };

        if($filter->getOrderBy()) {
            $query->andWhere('f.key = :orderKey');
            $query->setParameter('orderKey', $filter->getOrderBy());
            $query->orderBy('f.value', $filter->getOrderDirection());
        } else {
            $query->orderBy('calculated_score', 'DESC');
        }

        if($filter->getLimit() !== null) {
            $query->setMaxResults(intval($filter->getLimit()));
        }

        return $query;
    }

    public function countSearchResults(SearchFilter $filter)
    {
        $query = $this->createCountQuery($filter);
        $result = $query->getQuery()->getResult();

        if (isset($result[0]) && isset($result[0]['result_count'])) {
            return $result[0]['result_count'];
        }
        return 0;
    }

    /**
     * @param SearchFilter $filter
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createCountQuery(SearchFilter $filter)
    {
        $query = $this->createQueryBuilder('i');
        $query->select(
            'count(distinct(d.id)) AS result_count',
        );

        $query->innerJoin(Total::class, 't', 'WITH', 'i.word = t.word');
        $query->innerJoin(DataSet::class, 'd', 'WITH', 'i.dataSet = d');
        $query->leftJoin('d.filters', 'f');

        $i = 0;
        foreach($filter->getWords() as  $word) {
            $query->orWhere('i.word = :word_'.$i);
            $query->setParameter('word_'.$i, $word);
            $i++;
        }

        if($filter->getQueries()) {
            $i = 0;
            foreach($filter->getQueries() as $key => $filerQuery) {
                $ids = $this->createFilterIds($key, $filerQuery);
                $query->andWhere(sprintf('d.id IN (:filterIds_%s)', $i));
                $query->setParameter(sprintf('filterIds_%s', $i), $ids);
                $i++;
            }
        };

        if($filter->getOrderBy()) {
            $query->andWhere('f.key = :orderKey');
            $query->setParameter('orderKey', $filter->getOrderBy());
        }

        return $query;
    }

    private function createFilterIds($key, QueryInterface $filterQuery)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('d.id, f.key');
        $query->from(Filter::class, 'f');
        $query->join('f.dataSet', 'd');

        if($filterQuery instanceof MatchQuery) {
            $query->setParameter('key_1', $key);
            $query->setParameter('value_1', $filterQuery->getValue());
            $query->orWhere(sprintf('(f.key = :key_1 AND f.value %s :value_1)', $filterQuery->getOperator()));
        } elseif($filterQuery instanceof BetweenQuery) {
            $query->setParameter('key_1', $key);
            $query->setParameter('value_1', $filterQuery->getFrom());
            $query->setParameter('value_2', $filterQuery->getTo());
            $query->orWhere(sprintf(
                '(f.key = :key_1 AND f.value %s :value_1 AND f.value %s :value_2)',
                $filterQuery->getOperatorFrom() == BetweenQuery::OPERATOR_EQUAL_THAN ? '>=' : '>',
                $filterQuery->getOperatorTo() == BetweenQuery::OPERATOR_EQUAL_THAN ? '<=' : '<'
            ));
        }

        $result = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $ids = [];
        foreach($result as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
}
