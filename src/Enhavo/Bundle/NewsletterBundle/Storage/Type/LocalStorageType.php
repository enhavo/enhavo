<?php

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Repository\GroupRepository;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalStorageType extends AbstractStorageType
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RepositoryInterface
     */
    private $subscriberRepository;

    /**
     * @var RepositoryInterface
     */
    private $groupRepository;

    /** @var FactoryInterface */
    private $subscriberFactory;

    /**
     * LocalStorageType constructor.
     * @param EntityManagerInterface $entityManager
     * @param RepositoryInterface $subscriberRepository
     * @param RepositoryInterface $groupRepository
     * @param FactoryInterface $subscriberFactory
     */
    public function __construct(EntityManagerInterface $entityManager, RepositoryInterface $subscriberRepository, RepositoryInterface $groupRepository, FactoryInterface $subscriberFactory)
    {
        $this->entityManager = $entityManager;
        $this->subscriberRepository = $subscriberRepository;
        $this->groupRepository = $groupRepository;
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
        /** @var Group $group */
        foreach ($groups as $group) {
            /** @var LocalSubscriber $subscriber */
            foreach ($group->getSubscribers() as $subscriber) {
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
            'type' => $subscriber->getSubscription(),
        ]);

        return $receiver;
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed|void
     * @throws MappingException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $local = $this->getSubscriber($subscriber) ?? $this->createSubscriber($subscriber);

        /** @var Group $group */
        foreach ($options['groups'] as $code) {
            $group = $this->getGroup($code);
            if ($local->getGroups()->contains($group)) {
                continue;
            }
            $local->addGroup($group);
        }

        $this->entityManager->persist($local);
        $this->entityManager->flush();
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $local = $this->getSubscriber($subscriber);
        if ($local === null || count($local->getGroups()) == 0) {
            return false;
        }

        // subscriber has to be in ALL given groups to return true
        foreach ($options['groups'] as $code) {
            $mappedGroup = $this->getGroup($code);
            foreach ($local->getGroups() as $group) {
                if ($mappedGroup !== $group) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param SubscriberInterface $subscriber
     * @return LocalSubscriberInterface
     */
    private function getSubscriber(SubscriberInterface $subscriber): LocalSubscriberInterface
    {
        /** @var LocalSubscriberInterface $subscriber */
        $subscriber = $this->subscriberRepository->findOneBy([
            'email' => $subscriber->getEmail(),
            'subscription' => $subscriber->getSubscription()
        ]);

        return $subscriber;
    }

    /**
     * @param SubscriberInterface $subscriber
     * @return LocalSubscriberInterface
     */
    private function createSubscriber(SubscriberInterface $subscriber): LocalSubscriberInterface
    {
        /** @var LocalSubscriberInterface $local */
        $local = $this->subscriberFactory->createNew();
        $local->setCreatedAt(new \DateTime());
        $local->setEmail($subscriber->getEmail());
        $local->setSubscription($subscriber->getSubscription());

        return $local;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'groups'
        ]);
    }

    public static function getName(): ?string
    {
        return 'local';
    }

    /**
     * @param string $code
     * @return GroupInterface
     * @throws MappingException
     */
    private function getGroup(string $code): GroupInterface
    {
        /** @var Group $group */
        $group = $this->groupRepository->findOneBy([
            'code' => $code
        ]);

        if ($group) {
            return $group;
        }

        throw new MappingException(
            sprintf('Group with code "%s" does not exists.', $code)
        );
    }

}
