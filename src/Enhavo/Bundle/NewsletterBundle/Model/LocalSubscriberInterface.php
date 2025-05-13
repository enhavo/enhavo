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

/**
 * Interface LocalSubscriberInterface
 *
 * @method getGroups() ArrayCollection|Collection
 */
interface LocalSubscriberInterface extends GroupAwareInterface
{
    /**
     * Set email
     *
     * @param string $email
     *
     * @return SubscriberInterface
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @param string $key
     */
    public function setSubscription($key);

    /**
     * @return string
     */
    public function getSubscription();

    public function getToken(): ?string;

    public function setToken(?string $token): void;
}
