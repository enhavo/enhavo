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
