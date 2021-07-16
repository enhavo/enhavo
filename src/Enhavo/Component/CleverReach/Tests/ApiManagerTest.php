<?php

namespace Enhavo\Component\CleverReach\Tests;

use Enhavo\Component\CleverReach\ApiManager;
use Enhavo\Component\CleverReach\Http\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ApiManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ApiManagerTestDependencies();
        $dependencies->adapter = $this->getMockBuilder(AdapterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ApiManagerTestDependencies $dependencies)
    {
        $instance = new ApiManager($dependencies->adapter);
        return$instance;
    }

    public function testCreateSubscriber()
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

    public function testGetSubscriber()
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
