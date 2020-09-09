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
     * @param string|null $subscribtion
     */
    public function setSubscribtion(?string $subscribtion): void;

    /**
     * @return string|null
     */
    public function getSubscribtion(): ?string;
}
