<?php

namespace Enhavo\Bundle\NewsletterBundle\Factory;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;

class LocalSubscriberFactory extends Factory implements LocalSubscriberFactoryInterface
{
    public function __construct(
        string $className,
        private readonly EntityRepository $groupRepository,
    )
    {
        parent::__construct($className);
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
        /** @var GroupInterface $group */
        $group = $this->groupRepository->find($groupId);

        $subscriber = $this->createNew();
        $subscriber->addGroup($group);
        return $subscriber;
    }
}
