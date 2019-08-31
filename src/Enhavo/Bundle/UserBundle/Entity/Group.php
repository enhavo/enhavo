<?php

namespace Enhavo\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\UserBundle\Model\GroupInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\Group as BaseGroup;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Group
 */
class Group extends BaseGroup implements GroupInterface, ResourceInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('', array());
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add users
     *
     * @param UserInterface $users
     * @return Group
     */
    public function addUser(UserInterface $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param UserInterface $users
     */
    public function removeUser(UserInterface $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }
}
