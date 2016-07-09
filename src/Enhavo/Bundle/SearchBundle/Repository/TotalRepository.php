<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class TotalRepository extends EntityRepository
{
    public function getWordsToRemove()
    {
        $query = $this->createQueryBuilder('t');
        $query->select('t.word AS realword', 'i.word');
        $query->leftJoin('EnhavoSearchBundle:Index', 'i', 'WITH', 't.word = i.word');
        $query->where('i.word is NULL');
        return $query->getQuery()->getResult();
    }
}