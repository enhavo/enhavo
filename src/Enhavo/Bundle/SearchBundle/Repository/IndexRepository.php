<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch\SearchFilter;
use Enhavo\Bundle\SearchBundle\Model\Database\DataSet;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
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
            'sum(i.score * t.count) AS calculated_score'
        );
        $query->innerJoin(Total::class, 't', 'WITH', 'i.word = t.word');
        $query->innerJoin(DataSet::class, 'd', 'WITH', 'i.dataSet = d');

        $i = 0;
        foreach($filter->getWords() as  $word) {
            $query->orWhere('i.word = :word_'.$i);
            $query->setParameter('word_'.$i, $word);
            $i++;
        }

        $query->groupBy('d.id');
        $query->orderBy('calculated_score', 'DESC');
        return $query;
    }
}