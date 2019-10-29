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
     * @var SubscriberInterface
     */
    private $subscriber;

    /**
     * @var Collection
     */
    private $newsletters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriber = new \Doctrine\Common\Collections\ArrayCollection();
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
        $this->subscriber[] = $subscriber;

        return $this;
    }

    /**
     * Remove subscriber
     *
     * @param SubscriberInterface $subscriber
     */
    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscriber->removeElement($subscriber);
    }

    /**
     * Get subscriber
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * Add newsletter
     *
     * @param NewsletterInterface $newsletter
     * @return GroupInterface
     */
    public function addNewsletter(NewsletterInterface $newsletter): GroupInterface
    {
        $this->newsletters[] = $newsletter;

        return $this;
    }

    /**
     * Remove newsletter
     *
     * @param NewsletterInterface $newsletter
     */
    public function removeNewsletter(NewsletterInterface $newsletter)
    {
        $this->newsletters->removeElement($newsletter);
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
