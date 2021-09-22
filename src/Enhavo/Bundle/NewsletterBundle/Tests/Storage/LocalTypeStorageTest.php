<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Storage;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Factory\LocalSubscriberFactory;
use Enhavo\Bundle\NewsletterBundle\Factory\LocalSubscriberFactoryInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\LocalStorageType;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\StorageType;
use Enhavo\Bundle\NewsletterBundle\Tests\Mocks\GroupAwareSubscriberMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocalTypeStorageTest extends TestCase
{
    private function createDependencies(): LocalTypeStorageTestDependencies
    {
        $dependencies = new LocalTypeStorageTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->subscriberRepository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $dependencies->groupRepository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $dependencies->subscriberFactory = $this->getMockBuilder(LocalSubscriberFactoryInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance($type, $parents, $options): Storage
    {
        return new Storage($type, $parents, $options);
    }

    public function testAddSubscriber()
    {
        $dependencies = $this->createDependencies();
        $localSubscriber = new LocalSubscriber();
        $localSubscriber->setEmail('to@enhavo.com');
        $localSubscriber->setSubscription('default');
        $group = new Group();
        $group->setCode('local');
        $dependencies->subscriberRepository->expects($this->once())->method('findOneBy')->willReturn($localSubscriber);
        $dependencies->subscriberFactory->expects($this->never())->method('createFrom');
        $dependencies->groupRepository->expects($this->once())->method('findOneBy')->willReturnCallback(function ($criteria) use ($group) {
            $this->assertEquals(['code' => 'local'], $criteria);
            if ($criteria['code'] !== 'local') {
                return null;
            }
            return $group;
        });
        $dependencies->entityManager->expects($this->once())->method('persist');
        $dependencies->entityManager->expects($this->once())->method('flush');
        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => [
                'local'
            ]
        ]);
        /** @var SubscriberInterface|MockObject $subscriberMock */
        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriberMock->method('getEmail')->willReturn('to@enhavo.com');
        $subscriberMock->method('getSubscription')->willReturn('default');

        $storage->saveSubscriber($subscriberMock);

        $dependencies = $this->createDependencies();
        $dependencies->subscriberRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $dependencies->subscriberFactory = new LocalSubscriberFactory(LocalSubscriber::class, $dependencies->groupRepository);
        $dependencies->groupRepository->expects($this->once())->method('findOneBy')->willReturn($group);
        $dependencies->entityManager->expects($this->once())->method('persist')->willReturnCallback(function ($subscriber) {
            /** @var $subscriber LocalSubscriber */
            $this->assertInstanceOf(LocalSubscriber::class, $subscriber);
            $this->assertEquals('to@enhavo.com', $subscriber->getEmail());
            $this->assertEquals('default', $subscriber->getSubscription());
            $this->assertNotNull($subscriber->getCreatedAt());
        });
        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => [$group]
        ]);

        $storage->saveSubscriber($subscriberMock);

        $dependencies = $this->createDependencies();
        $dependencies->subscriberRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $dependencies->subscriberFactory->expects($this->once())->method('createFrom')->willReturn($localSubscriber);
        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => []
        ]);
        $this->expectException(NoGroupException::class);
        $storage->saveSubscriber($subscriberMock);
    }

    public function testExists()
    {
        $dependencies = $this->createDependencies();
        $localSubscriber = new LocalSubscriber();
                $localSubscriber->setEmail('to@enhavo.com');
        $localSubscriber->setSubscription('default');
        $group = new Group();
        $group->setCode('local');
        $localSubscriber->addGroup($group);
        $dependencies->subscriberRepository->expects($this->exactly(3))->method('findOneBy')->willReturn($localSubscriber);
        $dependencies->subscriberFactory->expects($this->never())->method('createFrom');
        $dependencies->groupRepository->expects($this->exactly(2))->method('findOneBy')->willReturnCallback(function ($criteria) use ($group) {
            $this->assertEquals(['code' => 'local'], $criteria);
            if ($criteria['code'] !== 'local') {
                return null;
            }
            return $group;
        });

        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => [
                'local'
            ]
        ]);
        /** @var SubscriberInterface|MockObject $subscriberMock */
        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriberMock->method('getEmail')->willReturn('to@enhavo.com');
        $subscriberMock->method('getSubscription')->willReturn('default');

        $this->assertTrue($storage->exists($subscriberMock));

        $localSubscriber->removeGroup($group);
        $this->assertFalse($storage->exists($subscriberMock));

        $localSubscriber->addGroup(new Group());
        $this->assertFalse($storage->exists($subscriberMock));
    }

    public function testGetReceivers()
    {
        $dependencies = $this->createDependencies();

        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => [
                'local'
            ]
        ]);

        $newsletter = new Newsletter();
        $group = new Group();
        $group->setCode('local');
        $subscriber = new LocalSubscriber();
        $subscriber->setEmail('to@enhavo.com');
        $subscriber->setSubscription('default');
        $subscriber->setToken('__TOKEN__');
        $group->addSubscriber($subscriber);
        $newsletter->addGroup($group);

        $receivers = $storage->getReceivers($newsletter);
        $this->assertCount(1, $receivers);
        /** @var Receiver $receiver */
        $receiver = $receivers[0];
        $this->assertEquals($receiver->getEmail(), $subscriber->getEmail());
        $this->assertEquals([
            'token' => $subscriber->getToken(),
            'type' => $subscriber->getSubscription(),
        ], $receiver->getParameters());
        $this->assertEquals($subscriber->getEmail(), $receiver->getEmail());

        $this->expectException(\InvalidArgumentException::class);
        $storage->getReceivers(new NotNewsletter());
    }

    public function testRemoveSubscriber()
    {
        $group = new Group();
        $group->setCode('local');

        $dependencies = $this->createDependencies();
        $dependencies->groupRepository->expects($this->once())->method('findOneBy')->willReturn($group);
        $dependencies->entityManager->expects($this->once())->method('remove');
        $dependencies->entityManager->expects($this->exactly(2))->method('flush');
        $dependencies->subscriberRepository->expects($this->exactly(3))->method('findOneBy')->willReturnCallback(function ($criteria) use ($group) {
            if ($criteria['subscription'] === 'missing') {
                return null;
            } else if ($criteria['subscription'] === 'found') {
                return new LocalSubscriber();

            } else if ($criteria['subscription'] === 'groups') {
                $subscriber = new LocalSubscriber();
                $subscriber->addGroup($group);
                return $subscriber;
            }
        });

        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
            'groups' => []
        ]);

//        $storage = $this->createInstance(new LocalStorageType($dependencies->entityManager, $dependencies->subscriberRepository, $dependencies->groupRepository, $dependencies->subscriberFactory), [new StorageType()], [
//            'groups' => [
//                'local'
//            ]
//        ]);

        $subscriber = new Subscriber();
        $subscriber->setEmail('to@enhavo.com');
        $subscriber->setSubscription('missing');

        $result = $storage->removeSubscriber($subscriber);
        $this->assertEquals(false, $result);

        $subscriber->setSubscription('found');

        $result = $storage->removeSubscriber($subscriber);
        $this->assertEquals(true, $result);

        $subscriber = new GroupAwareSubscriberMock();
        $subscriber->setEmail('to@enhavo.com');
        $subscriber->setSubscription('groups');
        $subscriber->addGroup($group);
        $result = $storage->removeSubscriber($subscriber);
        $this->assertEquals(true, $result);

    }

}

class NotNewsletter implements NewsletterInterface
{

    public function getId()
    {
    }

    public function getSlug()
    {
    }

    public function setSlug($slug)
    {
    }

    public function setSubject($subject)
    {
    }

    public function getSubject()
    {
    }

    public function setContent(NodeInterface $content)
    {

    }

    public function getContent()
    {
    }

    public function getTemplate(): ?string
    {
        return null;
    }

    public function setTemplate(?string $template)
    {

    }

    public function isPrepared()
    {

    }

    public function isSent()
    {

    }

    public function getState()
    {

    }

    public function setState(string $state)
    {

    }

    public function addReceiver(Receiver $receiver)
    {

    }

    public function removeReceiver(Receiver $receiver)
    {

    }

    public function getReceivers()
    {
        return [];
    }

    public function addAttachment(FileInterface $attachments)
    {

    }

    public function removeAttachment(FileInterface $attachments)
    {

    }

    public function getAttachments()
    {
        return [];
    }

    public function getStartAt(): ?\DateTime
    {
        return new \DateTime();
    }

    public function setStartAt(?\DateTime $startAt): void
    {

    }

    public function getFinishAt(): ?\DateTime
    {
        return new \DateTime();
    }

    public function setFinishAt(?\DateTime $finishAt): void
    {

    }

    public function getCreatedAt(): ?\DateTime
    {

    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {

    }
}

class LocalTypeStorageTestDependencies
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    public  $entityManager;

    /**
     * @var RepositoryInterface|MockObject
     */
    public  $subscriberRepository;

    /**
     * @var RepositoryInterface|MockObject
     */
    public  $groupRepository;

    /** @var LocalSubscriberFactoryInterface|MockObject */
    public $subscriberFactory;
}

