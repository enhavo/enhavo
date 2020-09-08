<?php

namespace Enhavo\Bundle\NewsletterBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\SliderBundle\Model\SliderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocalSubscriberFactory extends Factory
{
    /**
     * @var RepositoryInterface
     */
    private $groupRepository;

    public function __construct(string $className, RepositoryInterface $groupRepository)
    {
        parent::__construct($className);
        $this->groupRepository = $groupRepository;
    }

    public function createWithGroupId($groupId)
    {
        /** @var SliderInterface $slider */
        $group = $this->groupRepository->find($groupId);

        /** @var Group $group */
        $subscriber = $this->createNew();
        $subscriber->addGroup($group);
        return $subscriber;
    }
}
