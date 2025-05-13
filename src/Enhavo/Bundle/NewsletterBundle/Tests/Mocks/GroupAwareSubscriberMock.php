<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Mocks;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;

class GroupAwareSubscriberMock extends Subscriber implements GroupAwareInterface
{
    /**
     * @var Collection
     */
    private $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function addGroup(GroupInterface $group): void
    {
        $this->groups->add($group);
    }

    public function removeGroup(GroupInterface $group): void
    {
        $this->groups->removeElement($group);
    }

    /**
     * @return array|ArrayCollection|Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
