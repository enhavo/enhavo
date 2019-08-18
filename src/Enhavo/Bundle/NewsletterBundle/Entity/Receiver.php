<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.08.19
 * Time: 17:26
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;

class Receiver implements ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $eMail;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $sentAt;

    /**
     * @var Collection
     */
    private $parameters;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Subscriber $subscriber
     */
    private $subscriber;

    /**
     * @var Collection $trackings
     */
    private $tracking;

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
     * Constructor
     */
    public function __construct()
    {
        $this->tracking = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEMail(): string
    {
        return $this->eMail;
    }

    /**
     * @param string $eMail
     */
    public function setEMail(string $eMail): void
    {
        $this->eMail = $eMail;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getSentAt(): string
    {
        return $this->sentAt;
    }

    /**
     * @param string $sentAt
     */
    public function setSentAt(string $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return Collection
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * @param Collection $parameters
     */
    public function setParameters(Collection $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @param Subscriber $subscriber
     */
    public function setSubscriber(Subscriber $subscriber): void
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Add tracking
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Tracking $tracking
     */
    public function addTracking(Tracking $tracking): Receiver
    {
        $this->tracking[] = $tracking;
        $tracking->setReceiver($this);

        return $this;
    }

    /**
     * Remove receiver
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Tracking $tracking
     */
    public function removeTracking(Tracking $tracking): void
    {
        $this->tracking->removeElement($tracking);
        $tracking->setReceiver(null);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrackings()
    {
        return $this->tracking;
    }
}