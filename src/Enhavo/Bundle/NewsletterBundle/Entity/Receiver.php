<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.08.19
 * Time: 17:26
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
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
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $sentAt;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var string
     */
    private $token;

    /**
     * @var Collection $trackings
     */
    private $tracking;

    /**
     * @var NewsletterInterface $newsletter
     */
    private $newsletter;

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
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return null|\DateTime
     */
    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    /**
     * @param \DateTime $sentAt
     */
    public function setSentAt(\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
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

    /**
     * @param Tracking $tracking
     */
    public function addTracking(Tracking $tracking): void
    {
        $this->tracking[] = $tracking;
        $tracking->setReceiver($this);
    }

    /**
     * @param Tracking $tracking
     */
    public function removeTracking(Tracking $tracking): void
    {
        $this->tracking->removeElement($tracking);
        $tracking->setReceiver(null);
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getTracking()
    {
        return $this->tracking;
    }

    /**
     * @return NewsletterInterface
     */
    public function getNewsletter(): NewsletterInterface
    {
        return $this->newsletter;
    }

    /**
     * @param NewsletterInterface $newsletter
     */
    public function setNewsletter(NewsletterInterface $newsletter): void
    {
        $this->newsletter = $newsletter;
    }

    public function isSent()
    {
        return $this->getSentAt() !== null;
    }

    public function trackOpen()
    {
        $tracking = new Tracking();
        $tracking->setDate(new \DateTime());
        $tracking->setType(Tracking::TRACKING_OPEN);
        $tracking->setReceiver($this);
        $this->addTracking($tracking);
    }
}
