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
     * @param LocalSubscriberInterface $subscriber
     * @return GroupInterface
     */
    public function addSubscriber(LocalSubscriberInterface $subscriber): GroupInterface;

    /**
     * @param LocalSubscriberInterface $subscriber
     * @return mixed
     */
    public function removeSubscriber(LocalSubscriberInterface $subscriber);

    /**
     * Get subscriber
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscribers();

    /**
     * Get code for storage service mapping
     *
     * @return mixed
     */
    public function getCode();
}
