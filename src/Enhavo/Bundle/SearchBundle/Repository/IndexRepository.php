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
        $query = $this->createQueryBuilder('i');
        $wordCounter = 0;
        $query->select('sum(i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');
        foreach($conditions as $key => $value) {
            if ($key != 'NOT'){
                foreach($value as $currentValue) {
                    $currentValue = explode(" ", $currentValue);
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
                        $query->setParameter('word_' . $wordCounter, '%'.$currentValue.'%');
                        $query->andWhere('d.data LIKE :word_' . $wordCounter);
                        $wordCounter++;
                    }
                } else if ($key == 'OR') {
                    foreach ($value as $currentValue)
                    {
                        if(is_array($currentValue)){
                            $orString = "";
                            $first = true;
                            for($i = 0; $i < count($currentValue); $i++) {
                                $query->setParameter('word_' . $wordCounter, '%'.$currentValue[$i].'%');
                                if(!$first) {
                                    $orString = $orString.' OR ';
                                }
                                $first = false;
                                $orString = $orString.'d.data LIKE :word_' . $wordCounter;
                                $wordCounter++;
                            }
                            $query->andWhere($orString);
                        }
                    }
                } else if ($key = 'NOT') {
                    $notString = "";
                    $first = true;
                    for($i = 0; $i < count($conditions['NOT']); $i++) {
                        $query->setParameter('word_' . $wordCounter, '%'.$conditions['NOT'][$i].'%');
                        if(!$first) {
                            $notString = $notString.' AND ';
                        }
                        $first = false;
                        $notString = $notString.'d.data NOT LIKE :word_' . $wordCounter;
                        $wordCounter++;
                    }
                    $query->andWhere($notString);
                }
            }
        }

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
        $query = $this->createQueryBuilder('i');
        $query->setParameter('normalization', $normalization);
        $wordCounter = 0;
        $query->select('i.locale AS language', 'i.type AS type', 'i.id AS id', 'sum(:normalization * i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');
        foreach($conditions as $key => $value) {
            if ($key != 'NOT'){
                foreach($value as $currentValue) {
                    $currentValue = explode(" ", $currentValue);
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
                        $query->setParameter('word_' . $wordCounter, '%'.$currentValue.'%');
                        $query->andWhere('d.data LIKE :word_' . $wordCounter);
                        $wordCounter++;
                    }
                } else if ($key == 'OR') {
                    foreach ($value as $currentValue)
                    {
                        if(is_array($currentValue)){
                            $orString = "";
                            $first = true;
                            for($i = 0; $i < count($currentValue); $i++) {
                                $query->setParameter('word_' . $wordCounter, '%'.$currentValue[$i].'%');
                                if(!$first) {
                                    $orString = $orString.' OR ';
                                }
                                $first = false;
                                $orString = $orString.'d.data LIKE :word_' . $wordCounter;
                                $wordCounter++;
                            }
                            $query->andWhere($orString);
                        }
                    }
                } else if ($key = 'NOT') {
                    $notString = "";
                    $first = true;
                    for($i = 0; $i < count($conditions['NOT']); $i++) {
                        $query->setParameter('word_' . $wordCounter, '%'.$conditions['NOT'][$i].'%');
                        if(!$first) {
                            $notString = $notString.' AND ';
                        }
                        $first = false;
                        $notString = $notString.'d.data NOT LIKE :word_' . $wordCounter;
                        $wordCounter++;
                    }
                    $query->andWhere($notString);
                }
            }
        }

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
        return $query->getQuery()->getResult();
    }
}