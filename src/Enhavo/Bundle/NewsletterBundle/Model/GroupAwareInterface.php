<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

interface GroupAwareInterface
{
    public function addGroup(GroupInterface $group): void;

    public function removeGroup(GroupInterface $group): void;

    /**
     * @return array
     */
    public function getGroups();
}
