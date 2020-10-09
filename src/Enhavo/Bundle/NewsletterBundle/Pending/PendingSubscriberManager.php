<?php
/*
 * PendingSubscriberManager.php
 *
 * @since 07.09.20
 * @author blutze
 */

namespace Enhavo\Bundle\NewsletterBundle\Pending;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Repository\PendingSubscriberRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;

class PendingSubscriberManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var FactoryInterface */
    private $subscriberFactory;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * PendingSubscriberManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param FactoryInterface $subscriberFactory
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, FactoryInterface $subscriberFactory, TokenGeneratorInterface $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->subscriberFactory = $subscriberFactory;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function save(PendingSubscriber $subscriber, $flush = true)
    {
        $subscriber->setConfirmationToken($this->tokenGenerator->generateToken());
        $this->entityManager->persist($subscriber);

        if ($flush) {
            $this->entityManager->flush();
        }

    }

    public function remove(PendingSubscriber $subscriber, $flush = true)
    {
        $this->entityManager->remove($subscriber);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function removeBy(string $email, string $subscription, $flush = true)
    {
        /** @var PendingSubscriberRepository $repository */
        $repository = $this->entityManager->getRepository(PendingSubscriber::class);
        $repository->removeBy([
            'email' => $email,
            'subscription' => $subscription
        ]);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function find(int $id)
    {
        /** @var PendingSubscriberRepository $repository */
        $repository = $this->entityManager->getRepository(PendingSubscriber::class);

        return $repository->find($id);
    }

    public function findOneBy(string $email, string $subscription): ?PendingSubscriber
    {
        /** @var PendingSubscriberRepository $repository */
        $repository = $this->entityManager->getRepository(PendingSubscriber::class);

        return $repository->findOneBy([
            'email' => $email,
            'subscription' => $subscription
        ]);
    }

    public function findByToken(string $confirmationToken)
    {
        /** @var PendingSubscriberRepository $repository */
        $repository = $this->entityManager->getRepository(PendingSubscriber::class);

        return $repository->findOneBy([
            'confirmationToken' => $confirmationToken,
        ]);
    }

    public function createFrom(SubscriberInterface $subscriber): PendingSubscriber
    {
        /** @var PendingSubscriber $pending */
        $pending = $this->subscriberFactory->createNew();
        $pending->setEmail($subscriber->getEmail());
        $pending->setCreatedAt($subscriber->getCreatedAt());
        $pending->setSubscription($subscriber->getSubscription());
        $pending->setData($subscriber);

        return $pending;
    }
}
