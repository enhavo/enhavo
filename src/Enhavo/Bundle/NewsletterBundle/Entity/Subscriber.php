<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Subscriber
 */
class Subscriber implements ResourceInterface, SubscriberInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $activatedAt;

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
     * @return Subscriber
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Subscriber
     */
    public function setActive($active)
    {
        $this->active = $active;

        if($active && $this->activatedAt === null) {
            $this->activatedAt = new \DateTime();
        }

        if(!$active) {
            $this->activatedAt = null;
        }

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Subscriber
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
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
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     * @return Subscriber
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get activatedAt
     *
     * @return \DateTime 
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
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
}
