<?php

namespace Enhavo\Bundle\UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Group
 */
class Group implements GroupInterface
{
    /**
     * @var int|null
     */
    protected $id;

    /** @var string|null */
    protected $name;

    /**
     * @var string[]
     */
    protected $roles = [];

    /**
     * @var Collection<UserInterface>
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addUser(UserInterface $user)
    {
        $this->users->add($user);

    }

    public function removeUser(UserInterface $users)
    {
        $this->users->removeElement($users);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }
}
