<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class PendingSubscriber
 * @package Enhavo\Bundle\NewsletterBundle\Entity
 */
class PendingSubscriber implements ResourceInterface
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
    private $token;

    /**
     * @var string
     */
    private $subscribtion;

    /**
     * @var string
     */
    private $condition;

    /** @var object */
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
     * Set email
     *
     * @param string $email
     *
     * @return LocalSubscriber
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
     * Set token
     *
     * @param string $token
     *
     * @return LocalSubscriber
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
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
     * @return string
     */
    public function getSubscribtion()
    {
        return $this->subscribtion;
    }

    /**
     * @param string $subscribtion
     */
    public function setSubscribtion($subscribtion)
    {
        $this->subscribtion = $subscribtion;
    }

    /**
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }

    /**
     * @param object $data
     */
    public function setData(object $data): void
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->email;
    }
}
