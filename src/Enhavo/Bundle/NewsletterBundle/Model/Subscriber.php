<?php
/**
 * Subscriber.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Doctrine\Common\Collections\Collection;

class Subscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var Collection
     */
    private $groups;

    /**
     * @var string
     */
    private $subscribtion;

    /**
     * @var string|null
     */
    private $confirmationToken;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     * @return SubscriberInterface|void
     */
    public function addGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

    }

    /**
     * Remove group
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     */
    public function removeGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string|null
     */
    public function getSubscribtion(): ?string
    {
        return $this->subscribtion;
    }

    /**
     * @param string|null $subscribtion
     */
    public function setSubscribtion(?string $subscribtion): void
    {
        $this->subscribtion = $subscribtion;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $token
     */
    public function setConfirmationToken(?string $token): void
    {
        $this->confirmationToken = $token;
    }



    public function __toString()
    {
        return $this->email;
    }
}
