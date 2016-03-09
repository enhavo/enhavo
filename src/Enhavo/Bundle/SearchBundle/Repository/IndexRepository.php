<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Enhavo\Bundle\SearchBundle\Entity\Index;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\SearchBundle\Entity\Total;


class IndexRepository extends EntityRepository
{
    public function sumScoresOfWord($word)
    {
        $query = $this->createQueryBuilder('s');
        $query->setParameter('word', $word);
        $query->select('sum(s.score)');

        $query->andWhere('s.word = :word');

        return $query->getQuery()->getSingleResult();
    }

    public function getCalculatedScore($conditions, $matches, $simple)
    {

        //$words = explode(' ', $word);
        $query = $this->createQueryBuilder('i');
        $wordCounter = 0;
        $query->select('sum(i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');
        foreach($conditions as $condition) {
            if (is_array($condition)){
                foreach($condition as $currentValue) {
                    if(is_array($currentValue)){
                        foreach($currentValue as $currentValue1) {
                            $query->setParameter('word_' . $wordCounter, $currentValue1);
                            $query->orWhere('i.word = :word_' . $wordCounter);
                            $wordCounter++;
                        }
                    } else {
                        $query->setParameter('word_' . $wordCounter, $currentValue);
                        $query->orWhere('i.word = :word_' . $wordCounter);
                        $wordCounter++;
                    }
                }
            }
        }
        if(!$simple) {
            foreach($conditions as $key => $value) {
                if ($key == 'AND') {
                    foreach($value as $currentValue) {
                        $query->setParameter('word_' . $wordCounter, $currentValue);
                        $query->andWhere('i.word = :word_' . $wordCounter);
                        $wordCounter++;
                    }
                } else if ($key == 'OR') {
                    foreach ($value as $currentValue)
                    {
                        if(is_array($currentValue)){
                            $query->setParameter('word_' . $wordCounter, $currentValue[0]);
                            $wordBefore = $wordCounter;
                            $wordCounter++;
                            $query->setParameter('word_' . $wordCounter, $currentValue[1]);
                            $query->andWhere('i.word = :word_' . $wordBefore . ' OR i.word = :word_' .$wordCounter);
                            $wordCounter++;
                        }
                    }
                }
            }
        }

        //WHERE ( (i.word = 'zebra') OR (i.word = 'hamster') OR (i.word = 'wolf') ) AND ( (d.data LIKE '%zebra%') AND( (d.data LIKE '%hamster%') OR (d.data LIKE '%wolf%') ))
        $query->groupBy('d.id');
        // If the query is simple, we should have calculated the number of
        // matching words we need to find, so impose that criterion. For non-
        // simple queries, this condition could lead to incorrectly deciding not
        // to continue with the full query.
        if ($simple) {
            $query->setParameter('matches', $matches);
            $query->having('COUNT(d.id) >= :matches');
        }
        $query->orderBy('calculated_score', 'DESC');
        /*$query->where('i.word = :word_0');
        for($i = 1; $i < count($words); $i++) {
            $query->orWhere('i.word = :word_'.$i);
        }*/
        $checkQuery =  $query->getQuery()->getResult();
        if(!empty($checkQuery)) {
            $query->setMaxResults(1);
            $query->setFirstResult(0);
            return $query->getQuery()->getSingleResult();
        }
        return $checkQuery;
    }

    public function getSearchResults($conditions, $normalization, $matches, $simple)
    {
        //$words = explode(' ', $word);
        $query = $this->createQueryBuilder('i');
        $query->setParameter('normalization', $normalization);
        $wordCounter = 0;
        /*foreach($words as $currentWord) {
            $query->setParameter('word_'.$wordCounter, $currentWord);
            $wordCounter++;
        }*/
        $query->select('i.locale AS language', 'i.type AS type', 'i.id AS id', 'sum(:normalization * i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');

        $first = true;
        foreach($conditions as $key => $value) {
            if ($key == 'AND') {
                foreach ($value as $value1)
                {
                    if(!$simple) {
                        foreach ($value1 as $currentValue) {
                            $query->setParameter('word_' . $wordCounter, $currentValue);
                            if($first == true) {
                                $query->where('i.word = :word_' . $wordCounter);
                                $first = false;
                            } else {
                                $query->andWhere('i.word = :word_' . $wordCounter);
                            }
                            $wordCounter++;
                        }
                    } else {
                        $query->setParameter('word_' . $wordCounter, $value1);
                        if($first == true) {
                            $query->where('i.word = :word_' . $wordCounter);
                            $first = false;
                        } else {
                            $query->orWhere('i.word = :word_' . $wordCounter);
                        }
                        $wordCounter++;
                    }

                }
            } else if($key == 'OR') {
                foreach ($value as $currentValue1)
                {
                    if(is_array($currentValue1)) {
                        foreach ($currentValue1 as $currentValue2) {
                            $query->setParameter('word_' . $wordCounter, $currentValue2);
                            $query->orWhere('i.word = :word_' . $wordCounter);
                            $wordCounter++;
                        }
                    } else {
                        $query->setParameter('word_' . $wordCounter, $currentValue1);
                        $query->orWhere('i.word = :word_' . $wordCounter);
                        $wordCounter++;
                    }

                }
            }
        }

        /*$query->where('i.word = :word_0');
        for($i = 1; $i < count($words); $i++) {
            $query->orWhere('i.word = :word_'.$i);
        }*/
        $query->groupBy('d.id');
        // If the query is simple, we should have calculated the number of
        // matching words we need to find, so impose that criterion. For non-
        // simple queries, this condition could lead to incorrectly deciding not
        // to continue with the full query.
        if ($simple) {
            $query->setParameter('matches', $matches);
            $query->having('COUNT(d.id) >= :matches');
        }
        $query->orderBy('calculated_score', 'DESC');
        $test = $query->getQuery()->getResult();
        return $test;
    }
}