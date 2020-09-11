<?php
/*
 * LocalSubscriberInterface.php
 *
 * @since 07.09.20, 16:33
 * @author blutze
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;


interface LocalSubscriberInterface
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
     * Add group
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     * @return SubscriberInterface
     */
    public function addGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group);


    /**
     * Remove group
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     */
    public function removeGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group);


    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups();

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
