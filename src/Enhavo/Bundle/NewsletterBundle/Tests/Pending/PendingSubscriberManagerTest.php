<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Pending;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Repository\PendingSubscriberRepository;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PendingSubscriberManagerTest extends TestCase
{
    private function createDependencies(): PendingSubscriberManagerTestDependencies
    {
        $dependencies = new PendingSubscriberManagerTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->subscriberFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $dependencies->pendingRepository = $this->getMockBuilder(PendingSubscriberRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->entityManager->method('getRepository')->willReturn($dependencies->pendingRepository);
        return $dependencies;
    }

    private function createInstance(PendingSubscriberManagerTestDependencies $dependencies)
    {
        return new PendingSubscriberManager($dependencies->entityManager, $dependencies->subscriberFactory, $dependencies->tokenGenerator);
    }

    public function testSave()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $subscriber = new PendingSubscriber();
        $dependencies->entityManager->expects($this->exactly(2))->method('persist');
        $dependencies->entityManager->expects($this->once())->method('flush');
        $dependencies->tokenGenerator->expects($this->exactly(2))->method('generateToken')->willReturn('__TOKEN__');
        $manager->save($subscriber);
        $manager->save($subscriber, false);

        $this->assertEquals('__TOKEN__', $subscriber->getConfirmationToken());

    }

    public function testRemove()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $subscriber = new PendingSubscriber();
        $dependencies->entityManager->expects($this->exactly(2))->method('remove');
        $dependencies->entityManager->expects($this->once())->method('flush');
        $manager->remove($subscriber);
        $manager->remove($subscriber, false);
    }

    public function testRemoveBy()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $dependencies->pendingRepository->expects($this->exactly(2))->method('removeBy')->willReturnCallback(function ($criteria) {
            $this->assertEquals('remove@enhavo.com', $criteria['email']);
            $this->assertEquals('default', $criteria['subscription']);
        });
        $dependencies->entityManager->expects($this->once())->method('flush');
        $manager->removeBy('remove@enhavo.com', 'default');
        $manager->removeBy('remove@enhavo.com', 'default', false);

    }

    public function testFind()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $dependencies->pendingRepository->expects($this->once())->method('find')->willReturnCallback(function ($id) {
            $this->assertEquals(1, $id);
        });

        $manager->find(1);
    }

    public function testFindOneBy()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $dependencies->pendingRepository->expects($this->once())->method('findOneBy')->willReturnCallback(function ($criteria) {
            $this->assertEquals('find@enhavo.com', $criteria['email']);
            $this->assertEquals('default', $criteria['subscription']);
        });

        $manager->findOneBy('find@enhavo.com', 'default');
    }

    public function testFindByToken()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $dependencies->pendingRepository->expects($this->once())->method('findOneBy')->willReturnCallback(function ($criteria) {
            $this->assertEquals('__TOKEN__', $criteria['confirmationToken']);
        });

        $manager->findByToken('__TOKEN__');
    }

    public function testCcreateFrom()
    {
        $dependencies = $this->createDependencies();
        $dependencies->subscriberFactory->expects($this->once())->method('createNew')->willReturn(new PendingSubscriber());
        $manager = $this->createInstance($dependencies);
        $date = new \DateTime();
        $subscriber = new Subscriber();
        $subscriber->setEmail('create@enhavo.com');
        $subscriber->setCreatedAt($date);
        $subscriber->setSubscription('default');

        $pendingSubscriber = $manager->createFrom($subscriber);
        $this->assertEquals('create@enhavo.com', $pendingSubscriber->getEmail());
        $this->assertEquals($subscriber->getEmail(), $pendingSubscriber->getEmail());
        $this->assertEquals('default', $pendingSubscriber->getSubscription());
        $this->assertEquals($subscriber->getSubscription(), $pendingSubscriber->getSubscription());
        $this->assertEquals($subscriber, $pendingSubscriber->getData());
        $this->assertEquals($date, $pendingSubscriber->getCreatedAt());
        $this->assertEquals($subscriber->getCreatedAt(), $pendingSubscriber->getCreatedAt());
        $this->assertEquals('create@enhavo.com', (string)$pendingSubscriber);
    }
}

class PendingSubscriberManagerTestDependencies
{
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var FactoryInterface|MockObject */
    public $subscriberFactory;

    /** @var TokenGeneratorInterface|MockObject */
    public $tokenGenerator;

    /** @var PendingSubscriberRepository|MockObject */
    public $pendingRepository;
}
