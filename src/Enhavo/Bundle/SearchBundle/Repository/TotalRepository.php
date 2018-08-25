<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Enhavo\Bundle\SearchBundle\Model\Database\Index;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class TotalRepository extends EntityRepository
{
    public function findWordsToRemove()
    {
        $query = $this->createQueryBuilder('t');
        $query->leftJoin(Index::class, 'i', 'WITH', 't.word = i.word');
        $query->where('i.word is NULL');
        return $query->getQuery()->getResult();
    }

    public function findWords($words)
    {
        $query = $this->createQueryBuilder('t');
        $query->where('t.word IN (:words)');
        $query->setParameter('words', $words);
        return $query->getQuery()->getResult();
    }
}