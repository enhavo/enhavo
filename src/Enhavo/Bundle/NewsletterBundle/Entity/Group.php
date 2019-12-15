<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 13:07
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;


use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

class Group implements GroupInterface, ResourceInterface
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
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->newsletters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add subscriber
     *
     * @param SubscriberInterface $subscriber
     * @return GroupInterface
     */
    public function addSubscriber(SubscriberInterface $subscriber): GroupInterface
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * Remove subscriber
     *
     * @param SubscriberInterface $subscriber
     */
    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }

    /**
     * Get subscriber
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriber()
    {
        return $this->subscribers;
    }

    /**
     * Add newsletter
     *
     * @param Newsletter $newsletter
     * @return GroupInterface
     */
    public function addNewsletter(Newsletter $newsletter): GroupInterface
    {
        $this->newsletters[] = $newsletter;
        $newsletter->getGroups()->add($this);
        return $this;
    }

    /**
     * Remove newsletter
     *
     * @param Newsletter $newsletter
     */
    public function removeNewsletter(Newsletter $newsletter)
    {
        $this->newsletters->removeElement($newsletter);
        $newsletter->getGroups()->remove($this);
    }

    /**
     * Get newsletters
     *
     * @return \Doctrine\Common\Collections\Collection
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
