<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 13:07
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;


class Group
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
     * @var Subscriber
     */
    private $subscriber;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $condition;
    
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriber = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set condition
     *
     * @param string $condition
     * @return Group
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
     * Add subscriber
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Subscriber $subscriber
     * @return Group
     */
    public function addSubscriber(\Enhavo\Bundle\NewsletterBundle\Entity\Subscriber $subscriber)
    {
        $this->subscriber[] = $subscriber;

        return $this;
    }

    /**
     * Remove subscriber
     *
     * @param \Enhavo\Bundle\NewsletterBundle\Entity\Subscriber $subscriber
     */
    public function removeSubscriber(\Enhavo\Bundle\NewsletterBundle\Entity\Subscriber $subscriber)
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
}
