<?php

namespace Enhavo\Bundle\NewsletterBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class MailjetGroup implements GroupInterface
{
    /**
     * @var integer
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
     * @param int $id
     * @param string $name
     * @param string $code
     * @param \DateTime $lastChanged
     * @param \DateTime $lastMailing
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param LocalSubscriberInterface $subscriber
     * @return $this|GroupInterface
     */
    public function addSubscriber(LocalSubscriberInterface $subscriber): GroupInterface
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * @param LocalSubscriberInterface $subscriber
     * @return mixed|void
     */
    public function removeSubscriber(LocalSubscriberInterface $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }

    /**
     * @return Collection
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    public function __toString()
    {
        return $this->name;
    }
}
