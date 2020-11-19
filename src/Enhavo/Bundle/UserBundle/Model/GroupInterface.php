<?php

namespace Enhavo\Bundle\UserBundle\Model;

interface GroupInterface
{
    public function addRole($role);

    public function getId();

    public function getName();

    public function hasRole($role);

    public function getRoles();

    public function removeRole($role);

    public function setName($name);

    public function setRoles(array $roles);
}
