<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Doctrine\Common\Collections\Collection;

interface GroupInterface
{
    public function addSubscriber(LocalSubscriberInterface $subscriber): GroupInterface;

    public function removeSubscriber(LocalSubscriberInterface $subscriber);

    /**
     * Get subscriber
     *
     * @return Collection
     */
    public function getSubscribers();

    /**
     * Get code for storage service mapping
     */
    public function getCode();

    public function getName();
}
