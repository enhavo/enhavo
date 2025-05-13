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
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;

class Group implements GroupInterface
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

    /**
     * @var Collection
     */
    private $subscribers;

    /**
     * @var Collection
     */
    private $newsletters;

    /**
     * Constructor
     */
    public function __construct()
    {
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
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

    /**
     * @return Collection|Subscriber
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    /**
     * Add newsletter
     */
    public function addNewsletter(Newsletter $newsletter): GroupInterface
    {
        $this->newsletters[] = $newsletter;
        $newsletter->getGroups()->add($this);

        return $this;
    }

    /**
     * Remove newsletter
     */
    public function removeNewsletter(Newsletter $newsletter)
    {
        $this->newsletters->removeElement($newsletter);
        $newsletter->removeGroup($this);
    }

    /**
     * Get newsletters
     *
     * @return Collection
     */
    public function getNewsletters()
    {
        return $this->newsletters;
    }

    public function __toString()
    {
        return $this->name;
    }
}
