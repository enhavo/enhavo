<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

class Receiver
{
    /**
     * @var int
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
     * @var Collection
     */
    private $tracking;

    /**
     * @var NewsletterInterface
     */
    private $newsletter;

    /**
     * Get id
     *
     * @return int
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
        $this->tracking = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function addTracking(Tracking $tracking): void
    {
        $this->tracking[] = $tracking;
        $tracking->setReceiver($this);
    }

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

    public function getNewsletter(): NewsletterInterface
    {
        return $this->newsletter;
    }

    public function setNewsletter(NewsletterInterface $newsletter): void
    {
        $this->newsletter = $newsletter;
    }

    public function isSent()
    {
        return null !== $this->getSentAt();
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
