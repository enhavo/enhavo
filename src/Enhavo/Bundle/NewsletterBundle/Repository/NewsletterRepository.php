<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
