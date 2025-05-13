<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CleverReachGroup implements GroupInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /** @var \DateTime */
    private $lastChanged;

    /** @var \DateTime */
    private $lastMailing;

    /**
     * @var Collection
     */
    private $subscribers;

    /**
     * @var Collection
     */
    private $newsletters;

    /**
     * Group constructor.
     */
    public function __construct(int $id, string $name, string $code, \DateTime $lastChanged, \DateTime $lastMailing)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->lastChanged = $lastChanged;
        $this->lastMailing = $lastMailing;

        $this->subscribers = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return $this|GroupInterface
     */
    public function addSubscriber(LocalSubscriberInterface $subscriber): GroupInterface
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * @return mixed|void
     */
    public function removeSubscriber(LocalSubscriberInterface $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }

    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    public function __toString()
    {
        return $this->name;
    }
}
