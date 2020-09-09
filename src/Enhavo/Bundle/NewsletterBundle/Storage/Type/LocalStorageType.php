<?php

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Repository\GroupRepository;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocalStorageType extends AbstractStorageType
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /** @var FactoryInterface */
    private $subscriberFactory;

    /**
     * LocalStorageType constructor.
     * @param EntityManagerInterface $entityManager
     * @param RepositoryInterface $repository
     * @param FactoryInterface $subscriberFactory
     */
    public function __construct(EntityManagerInterface $entityManager, RepositoryInterface $repository, FactoryInterface $subscriberFactory)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->subscriberFactory = $subscriberFactory;
    }

    public function getReceivers(NewsletterInterface $newsletter, array $options): array
    {
        if (!($newsletter instanceof Newsletter)) {
            throw new \InvalidArgumentException('Newsletter type is not supported by provider');
        }

        $subscribers = [];
        $receivers = [];
        $groups = $newsletter->getGroups();
        foreach ($groups as $group) {
            /** @var LocalSubscriber $subscriber */
            foreach ($group->getSubscriber() as $subscriber) {
                if (!isset($subscribers[$subscriber->getId()])) {
                    $subscribers[$subscriber->getId()] = $subscriber;
                    $receivers[] = $this->createReceiver($subscriber);
                }
            }
        }

        return $receivers;
    }

    private function createReceiver(LocalSubscriber $subscriber)
    {
        $receiver = new Receiver();
        $receiver->setEmail($subscriber->getEmail());
        $receiver->setParameters([
            'token' => $subscriber->getToken(),
            'type' => $subscriber->getSubscribtion(),
        ]);

        return $receiver;
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        /** @var LocalSubscriberInterface $local */
        $local = $this->subscriberFactory->createNew();
        $local->setCreatedAt(new \DateTime());
        $local->setEmail($subscriber->getEmail());
        $local->setSubscribtion($subscriber->getSubscribtion());
        $this->entityManager->persist($local);
        $this->entityManager->flush();
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        // subscriber has to be in ALL given groups to return true
        if ($this->getSubscriber($subscriber->getEmail()) === null) {
            return false;
        }

        $groupsSubscriberIsIn = $this->getSubscriber($subscriber->getEmail())->getGroups()->getValues();

        $subscriberGroupNames = [];
        /**
         * @var $group Group
         */
        foreach ($groupsSubscriberIsIn as $group) {
            $subscriberGroupNames[] = $group->getName();
        }

        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        foreach ($subscriber->getGroups() as $group) {
            $group = $groupRepository->findOneBy(['name' => $group->getName()]);
            if ($group === null) {
                return false;
            }
            if (!in_array($group->getName(), $subscriberGroupNames)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $email
     * @return SubscriberInterface|null
     */
    public function getSubscriber(string $email)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $this->repository->findOneBy([
            'email' => $email
        ]);

        return $subscriber;
    }

    public static function getName(): ?string
    {
        return 'local';
    }
}
