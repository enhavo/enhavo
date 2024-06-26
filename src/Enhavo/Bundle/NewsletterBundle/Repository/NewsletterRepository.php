<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class NewsletterRepository extends EntityRepository
{
    public function findNotSentNewsletters()
    {
        $qb = $this->createQueryBuilder('newsletter')
            ->where('newsletter.state IN (:states)')
            ->setParameter('states', [NewsletterInterface::STATE_PREPARED, NewsletterInterface::STATE_SENDING]);

        return $qb->getQuery()->getResult();
    }
}
