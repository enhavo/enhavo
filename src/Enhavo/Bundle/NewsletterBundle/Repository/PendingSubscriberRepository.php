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
