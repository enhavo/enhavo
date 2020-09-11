<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Subscriber
 */
class LocalSubscriber implements ResourceInterface, LocalSubscriberInterface
{
    /**
     * @var integer
     */
    private $id;

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
    private $subscription;

    /**
     * @var string
     */
    private $condition;

    /** @var string|null */
    private $token;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return LocalSubscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
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
     * Set condition
     *
     * @param string $condition
     * @return LocalSubscriber
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Add group
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     * @return LocalSubscriber
     */
    public function addGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
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
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param string $key
     */
    public function setSubscription($key)
    {
        $this->subscription = $key;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function __toString()
    {
        return $this->email;
    }
}
