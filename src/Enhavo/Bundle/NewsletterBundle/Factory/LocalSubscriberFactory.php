<?php

namespace Enhavo\Bundle\NewsletterBundle\Factory;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\SliderBundle\Model\SliderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocalSubscriberFactory extends Factory implements LocalSubscriberFactoryInterface
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

    public function createFrom(SubscriberInterface $subscriber): LocalSubscriberInterface
    {
        $local = $this->createNew();
        $local->setCreatedAt(new \DateTime());
        $local->setEmail($subscriber->getEmail());
        $local->setSubscription($subscriber->getSubscription());

        return $local;
    }

    public function createWithGroupId($groupId): LocalSubscriberInterface
    {
        /** @var SliderInterface $slider */
        $group = $this->groupRepository->find($groupId);

        /** @var Group $group */
        $subscriber = $this->createNew();
        $subscriber->addGroup($group);
        return $subscriber;
    }
}
