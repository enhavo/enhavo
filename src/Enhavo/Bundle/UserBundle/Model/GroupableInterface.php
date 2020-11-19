<?php

namespace Enhavo\Bundle\UserBundle\Model;

interface GroupableInterface
{
    public function getGroups();

    public function getGroupNames();

    public function hasGroup($name);

    public function addGroup(GroupInterface $group);

    public function removeGroup(GroupInterface $group);
}
