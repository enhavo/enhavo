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

    public function getCalculatedScore($word)
    {
        $words = explode(' ', $word);
        $query = $this->createQueryBuilder('i');
        $wordCounter = 0;
        foreach($words as $currentWord) {
            $query->setParameter('word_'.$wordCounter, $currentWord);
            $wordCounter++;
        }
        $query->select('sum(i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');
        $query->where('i.word = :word_0');
        for($i = 1; $i < count($words); $i++) {
            $query->orWhere('i.word = :word_'.$i);
        }
        $test = $query->getQuery()->getSingleResult();
        return $test;
    }

    public function getSearchResults($word, $normalization)
    {
        $words = explode(' ', $word);
        $query = $this->createQueryBuilder('i');
        $query->setParameter('normalization', $normalization);
        $wordCounter = 0;
        foreach($words as $currentWord) {
            $query->setParameter('word_'.$wordCounter, $currentWord);
            $wordCounter++;
        }
        $query->select('i.locale AS language', 'i.type AS type', 'i.id AS id', 'sum(:normalization * i.score * t.count) AS calculated_score');
        $query->innerJoin('EnhavoSearchBundle:Total', 't', 'WITH', 'i.word = t.word');
        $query->innerJoin('EnhavoSearchBundle:Dataset', 'd', 'WITH', 'i.dataset = d');

        $query->where('i.word = :word_0');
        for($i = 1; $i < count($words); $i++) {
            $query->orWhere('i.word = :word_'.$i);
        }
        $query->groupBy('d.reference');
        // If the query is simple, we should have calculated the number of
        // matching words we need to find, so impose that criterion. For non-
        // simple queries, this condition could lead to incorrectly deciding not
        // to continue with the full query.
        //if ($simple) {
            $query->setParameter('matches', count($words));
            $query->having('COUNT(d.reference) >= :matches');
        //}
        $query->orderBy('calculated_score', 'DESC');
        $test = $query->getQuery()->getResult();
        return $test;
    }
}