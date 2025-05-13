<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Factory\LocalSubscriberFactoryInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalStorageType extends AbstractStorageType
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityRepository $subscriberRepository,
        private readonly EntityRepository $groupRepository,
        private readonly LocalSubscriberFactoryInterface $subscriberFactory,
    ) {
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
     * @throws MappingException
     * @throws NoGroupException
     *
     * @return mixed|void
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $localSubscriber = $this->getLocalSubscriber($subscriber)
            ?? $this->createSubscriber($subscriber);

        $groups = $options['groups'];
        if ($subscriber instanceof GroupAwareInterface) {
            $groups = $subscriber->getGroups();
        }

        if (0 === count($groups)) {
            throw new NoGroupException('no groups given');
        }

        foreach ($groups as $group) {
            $group = $group instanceof Group ? $this->findGroup($group->getCode()) : $this->findGroup($group);
            if ($localSubscriber->getGroups()->contains($group)) {
                continue;
            }
            $localSubscriber->addGroup($group);
        }

        $this->entityManager->persist($localSubscriber);
        $this->entityManager->flush();
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $localSubscriber = $this->getLocalSubscriber($subscriber);

        if (!$localSubscriber) {
            return false;
        }

        $groups = $options['groups'];
        if ($subscriber instanceof GroupAwareInterface) {
            $groups = $subscriber->getGroups();
        }

        if (count($groups)) {
            foreach ($groups as $group) {
                $group = $group instanceof Group ? $this->findGroup($group->getCode()) : $this->findGroup($group);
                if (!$localSubscriber->getGroups()->contains($group)) {
                    continue;
                }
                $localSubscriber->removeGroup($group);
            }
        } else {
            $this->entityManager->remove($localSubscriber);
        }

        $this->entityManager->flush();

        return true;
    }

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        $localSubscriber = $this->getLocalSubscriber($subscriber);

        if (!$localSubscriber) {
            return null;
        }

        if ($subscriber instanceof GroupAwareInterface) {
            foreach ($localSubscriber->getGroups() as $group) {
                $group = $group instanceof Group ? $this->findGroup($group->getCode()) : $this->findGroup($group);
                $subscriber->addGroup($group);
            }
        }

        $subscriber->setSubscription($localSubscriber->getSubscription());
        $subscriber->setCreatedAt($localSubscriber->getCreatedAt());

        return $subscriber;
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        $local = $this->getLocalSubscriber($subscriber);
        if (null === $local || 0 == count($local->getGroups())) {
            return false;
        }

        // subscriber has to be in ALL given groups to return true
        $groups = $options['groups'];
        if ($subscriber instanceof GroupAwareInterface) {
            $groups = $subscriber->getGroups();
        }
        foreach ($groups as $group) {
            $group = $group instanceof Group ? $this->findGroup($group->getCode()) : $this->findGroup($group);
            if (!$local->getGroups()->contains($group)) {
                return false;
            }
        }

        return true;
    }

    private function getLocalSubscriber(SubscriberInterface $subscriber): ?LocalSubscriberInterface
    {
        /** @var LocalSubscriberInterface $subscriber */
        $subscriber = $this->subscriberRepository->findOneBy([
            'email' => $subscriber->getEmail(),
            'subscription' => $subscriber->getSubscription(),
        ]);

        return $subscriber;
    }

    private function createSubscriber(SubscriberInterface $subscriber): LocalSubscriberInterface
    {
        return $this->subscriberFactory->createFrom($subscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => [],
        ]);
    }

    public static function getName(): ?string
    {
        return 'local';
    }

    /**
     * @throws MappingException
     */
    private function findGroup(string $code): GroupInterface
    {
        /** @var Group $group */
        $group = $this->groupRepository->findOneBy([
            'code' => $code,
        ]);

        if ($group) {
            return $group;
        }

        throw new MappingException(sprintf('Group with code "%s" does not exist.', $code));
    }
}
