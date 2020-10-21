<?php
/*
 * LocalSubscriberInterface.php
 *
 * @since 07.09.20, 16:33
 * @author blutze
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

/**
 * Interface LocalSubscriberInterface
 * @package Enhavo\Bundle\NewsletterBundle\Model
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
     *
     */
    public function setSubscription($key);

    /**
     * @return string
     */
    public function getSubscription();

    /**
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void;


}
