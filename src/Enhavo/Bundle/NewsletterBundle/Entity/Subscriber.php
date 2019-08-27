<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\Collection;
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
    private $active = false;

    /**
     * @var \DateTime
     */
    private $activatedAt;

    /**
     * @var Group
     */
    private $group;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $condition;

    /**
     * @var Collection
     */
    private $receiver;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
        $this->receiver = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function isActive()
    {
        return (boolean)$this->getActive();
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

    /**
     * Set condition
     *
     * @param string $condition
     * @return Subscriber
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
     * @return Subscriber
     */
    public function addGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group)
    {
        $this->group[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Group $group
     */
    public function removeGroup(\Enhavo\Bundle\NewsletterBundle\Entity\Group $group)
    {
        $this->group->removeElement($group);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Add receiver
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Receiver $receiver
     * @return Subscriber
     */
    public function addReceiver(Receiver $receiver)
    {
        $this->receiver[] = $receiver;
        $receiver->setSubscriber($this);

        return $this;
    }

    /**
     * Remove receiver
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Receiver $receiver
     */
    public function removeReceiver(Receiver $receiver)
    {
        $this->receiver->removeElement($receiver);
        $receiver->setSubscriber(null);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceivers()
    {
        return $this->receiver;
    }

    public function __toString()
    {
        return $this->email;
    }
}
