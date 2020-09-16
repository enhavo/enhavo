<?php
/**
 * SubscriberInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

interface SubscriberInterface
{
    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $token
     */
    public function setConfirmationToken(?string $token): void;

    /**
     * Get token
     *
     * @return string
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

    /**
     * @param string|null $subscription
     */
    public function setSubscription(?string $subscription): void;

    /**
     * @return string|null
     */
    public function getSubscription(): ?string;
}
