<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-29
 * Time: 18:22
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

interface GroupInterface
{
    /**
     * Add subscriber
     *
     * @param SubscriberInterface $subscriber
     * @return GroupInterface
     */
    public function addSubscriber(SubscriberInterface $subscriber): GroupInterface;

    /**
     * Remove subscriber
     *
     * @param SubscriberInterface $subscriber
     */
    public function removeSubscriber(SubscriberInterface $subscriber);

    /**
     * Get subscriber
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriber();

    /**
     * Get code for storage service mapping
     *
     * @return mixed
     */
    public function getCode();
}
