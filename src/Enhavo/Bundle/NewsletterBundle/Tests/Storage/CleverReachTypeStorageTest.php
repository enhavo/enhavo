<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Storage;


use Enhavo\Bundle\NewsletterBundle\Client\CleverReachClient;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Exception\NotFoundException;
use Enhavo\Bundle\NewsletterBundle\Exception\RemoveException;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\CleverReachStorageType;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\StorageType;
use Enhavo\Bundle\NewsletterBundle\Tests\Mocks\GroupAwareSubscriberMock;
use Enhavo\Component\CleverReach\ApiManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CleverReachTypeStorageTest extends TestCase
{
    private function createDependencies(): CleverReachTypeStorageTestDependencies
    {
        $dependencies = new CleverReachTypeStorageTestDependencies();
        $dependencies->subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->apiManager = $this->getMockBuilder(ApiManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->client = new CleverReachClientMock($dependencies->eventDispatcher);
        $dependencies->client->_apiManager = $dependencies->apiManager;
        return $dependencies;
    }

    private function createInstance($type, $parents, $options): Storage
    {
        return new Storage($type, $parents, $options);
    }

    public function testSaveSubscriberInsertException()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
        ]);
        $dependencies->apiManager->expects($this->exactly(1))->method('getSubscriber')->willReturn([]);
        $dependencies->apiManager->method('createSubscriber')->willReturn([]);
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $this->expectException(InsertException::class);
        $instance->saveSubscriber($dependencies->subscriber);
    }

    public function testSaveSubscriberExists()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
        ]);
        $dependencies->apiManager->expects($this->exactly(2))->method('getSubscriber')->willReturn(['id' => 1]);
        $dependencies->apiManager->expects($this->never())->method('createSubscriber')->willReturn([]);
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');

        $instance->saveSubscriber($dependencies->subscriber);
    }

    public function testSaveSubscriber()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(2))->method('getSubscriber')->willReturn([]);
        $dependencies->apiManager->expects($this->exactly(2))->method('createSubscriber')->willReturnCallback(function ($email, $group, $active, $attributes, $globalAttributes) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertTrue($active);
            $this->assertIsNumeric($group);
            $this->assertEquals(['email' => 'to@enhavo.com'], $attributes);
            $this->assertEquals(['subscription' => 'default'], $globalAttributes);

            return ['id'=>1];
        });
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $dependencies->subscriber->method('getSubscription')->willReturn('default');
        $instance->saveSubscriber($dependencies->subscriber);
    }

    public function testSaveSubscriberGroupAware()
    {
        $dependencies = $this->createDependencies();
        $dependencies->subscriber = new GroupAwareSubscriberMock();
        $dependencies->subscriber->setSubscription('default');
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(1))->method('createSubscriber')->willReturnCallback(function ($email, $group, $active, $attributes, $globalAttributes) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertTrue($active);
            $this->assertEquals(11, $group);
            $this->assertEquals(['email' => 'to@enhavo.com'], $attributes);
            $this->assertEquals(['subscription' => 'default'], $globalAttributes);

            return ['id'=>1];
        });
        $dependencies->subscriber->setEmail('to@enhavo.com');
        $dependencies->subscriber->setSubscription('default');
        /** @var GroupInterface|MockObject $group */
        $group = $this->getMockBuilder(GroupInterface::class)->getMock();
        $group->method('getCode')->willReturn('11');
        $dependencies->subscriber->addGroup($group);

        $instance->saveSubscriber($dependencies->subscriber);

        $dependencies->subscriber->removeGroup($group);
        $this->expectException(NoGroupException::class);
        $instance->saveSubscriber($dependencies->subscriber);
    }

    public function testRemoveSubscriber()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(2))->method('getSubscriber')->willReturn(['id' => 1]);
        $dependencies->apiManager->expects($this->exactly(2))->method('deleteSubscriber')->willReturnCallback(function ($email, $group) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertIsNumeric($group);

            return true;
        });
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $dependencies->subscriber->method('getSubscription')->willReturn('default');
        $instance->removeSubscriber($dependencies->subscriber);
    }

    public function testGetSubscriber()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(1))->method('getSubscriber')->willReturnCallback(function ($email) {
            $result = [
                'email' => $email,
                'attributes' => [],
                'global_attributes' => [],
            ];

            return $result;
        });
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $dependencies->subscriber->method('getSubscription')->willReturn('default');
        $subscriber = $instance->getSubscriber($dependencies->subscriber);

        $this->assertInstanceOf(SubscriberInterface::class, $subscriber);
        $this->assertEquals('to@enhavo.com', $subscriber->getEmail());
    }

    public function testGetSubscriberError()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(1))->method('getSubscriber')->willReturnCallback(function ($email) {
            return [
                'error' => 404
            ];
        });
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $dependencies->subscriber->method('getSubscription')->willReturn('default');
        $this->expectException(NotFoundException::class);
        $instance->getSubscriber($dependencies->subscriber);

    }

    public function testRemoveSubscriberFail()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->once())->method('getSubscriber')->willReturn(['id' => 1]);
        $dependencies->apiManager->expects($this->once())->method('deleteSubscriber')->willReturn(false);
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $dependencies->subscriber->method('getSubscription')->willReturn('default');
        $this->expectException(RemoveException::class);
        $instance->removeSubscriber($dependencies->subscriber);
    }

    public function testExists()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance(new CleverReachStorageType($dependencies->client), [new StorageType()], [
            'client_secret' => '_secret_',
            'client_id' => '_id_',
            'groups' => ['11', '22'],
            'attributes' => [
                'email' => 'email',
            ],
            'global_attributes' => [
                'subscription' => 'subscription',
            ],
        ]);
        $dependencies->apiManager->expects($this->exactly(2))->method('getSubscriber')->willReturnCallback(function ($email, $group) {
            if ($group !== 11) {
                return [];
            }

            return ['id' => 1];
        });
        $dependencies->subscriber->method('getEmail')->willReturn('to@enhavo.com');

        $this->assertFalse($instance->exists($dependencies->subscriber));
    }

}

class CleverReachTypeStorageTestDependencies
{
    /** @var CleverReachClient */
    public $client;

    /** @var SubscriberInterface|MockObject */
    public $subscriber;

    /** @var ApiManager|MockObject */
    public $apiManager;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
}

class CleverReachClientMock extends CleverReachClient
{
    public $_apiManager;

    protected function getApiManager(): ApiManager
    {
        return $this->_apiManager;
    }
}
