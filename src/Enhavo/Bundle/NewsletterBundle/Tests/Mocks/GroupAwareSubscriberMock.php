<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Mocks;




use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;

class GroupAwareSubscriberMock extends Subscriber implements  GroupAwareInterface
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

    /**
     * @param GroupInterface $group
     */
    public function addGroup(GroupInterface $group): void
    {
        $this->groups->add($group);
    }

    /**
     * @param GroupInterface $group
     */
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
