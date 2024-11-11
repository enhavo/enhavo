<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

/**
 * Class PendingSubscriber
 * @package Enhavo\Bundle\NewsletterBundle\Entity
 */
class PendingSubscriber
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
     * @var string
     */
    private $confirmationToken;

    /**
     * @var string
     */
    private $subscription;

    /** @var SubscriberInterface */
    private $data;

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
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @param $confirmationToken
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
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
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param string $subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return SubscriberInterface
     */
    public function getData(): SubscriberInterface
    {
        return $this->data;
    }

    /**
     * @param SubscriberInterface $data
     */
    public function setData(SubscriberInterface $data): void
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->email;
    }
}
