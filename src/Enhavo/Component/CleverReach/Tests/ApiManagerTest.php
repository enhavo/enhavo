<?php

namespace Enhavo\Component\CleverReach\Tests;

use Enhavo\Component\CleverReach\ApiManager;
use Enhavo\Component\CleverReach\Http\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ApiManagerTest extends TestCase
{
    private function createDependencies(): ApiManagerTestDependencies
    {
        $dependencies = new ApiManagerTestDependencies();
        $dependencies->adapter = $this->getMockBuilder(AdapterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ApiManagerTestDependencies $dependencies): ApiManager
    {
        return new ApiManager($dependencies->adapter);
    }

    public function testCreateSubscriber(): void
    {
        $dependencies = $this->createDependencies();

        $dependencies->adapter->method('action')->willReturn([
            'email' => 'john.doe@example.org'
        ]);

        $manager = $this->createInstance($dependencies);

        $data = $manager->createSubscriber('john.doe@example.org', 123, false, [
            'salutation' => 'Mr.',
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        $this->assertArrayHasKey('email', $data);
        $this->assertEquals('john.doe@example.org', $data['email']);
    }

    public function testCreateSubscriberWithTags(): void
    {
        $dependencies = $this->createDependencies();

        $groupId = 456;
        $tags = ['tag 1', 'tag 2'];

        $email = 'johnny.doe@example.org';
        $dependencies->adapter
            ->method('action')
            ->with('post',
                '/v3/groups.json/' . $groupId .'/receivers',
                [
                    'email' => $email,
                    'registered' => time(),
                    'activated' => false,
                    'attributes' => ['salutation' => 'Mr.', 'firstname' => 'John', 'lastname' => 'Doe',],
                    'global_attributes' => [],
                    'tags' => $tags,
                ])
            ->willReturn(['email' => $email, 'tags' => $tags]);

        $manager = $this->createInstance($dependencies);

        $data = $manager->createSubscriber($email, $groupId, false, [
            'salutation' => 'Mr.',
            'firstname' => 'John',
            'lastname' => 'Doe',
        ], [], $tags);

        $this->assertArrayHasKey('tags', $data);
        $this->assertSame($tags, $data['tags']);
    }

    public function testGetSubscriber(): void
    {
        $dependencies = $this->createDependencies();

        $dependencies->adapter->method('action')->willReturn([
            'error' => [
                'code' => 404,
                'message' => 'Not Found: invalid receiver',
            ]
        ]);

        $manager = $this->createInstance($dependencies);

        $data = $manager->getSubscriber('john.doe@example.org', 123);

        $this->assertArrayHasKey('error', $data);
    }
}

class ApiManagerTestDependencies
{
    /** @var AdapterInterface|MockObject */
    public $adapter;
}
