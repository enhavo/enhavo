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

interface SubscriberInterface
{
    public function setEmail(?string $email): void;

    public function getEmail(): ?string;

    public function setConfirmationToken(?string $token): void;

    /**
     * Get token
     */
    public function getConfirmationToken(): ?string;

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt);

    public function setSubscription(?string $subscription): void;

    public function getSubscription(): ?string;
}
