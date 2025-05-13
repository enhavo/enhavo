<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Model;

interface GroupableInterface
{
    public function getGroups();

    public function getGroupNames();

    public function hasGroup($name);

    public function addGroup(GroupInterface $group);

    public function removeGroup(GroupInterface $group);
}
