<?php

namespace Enhavo\Bundle\SearchBundle\Repository;

use Enhavo\Bundle\SearchBundle\Entity\Index;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
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
}