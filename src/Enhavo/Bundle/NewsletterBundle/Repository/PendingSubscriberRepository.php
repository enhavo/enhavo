<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class PendingSubscriberRepository extends EntityRepository
{
    public function removeBy(array $criteria)
    {
        $subscribers = $this->findBy($criteria);

        foreach ($subscribers as $subscriber) {
            $this->remove($subscriber);
        }
    }
}
