<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Provider;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Provider\GroupProvider;
use PHPUnit\Framework\TestCase;

class GroupProviderTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new GroupProviderDependency();
        $dependencies->testReceiver = [
            'token' => '123',
            'parameters' => ['hello' => 'world'],
        ];

        return $dependencies;
    }

    public function createInstance(GroupProviderDependency $dependency)
    {
        $instance = new GroupProvider($dependency->testReceiver);

        return $instance;
    }

    public function testGetReceivers()
    {
        $dependencies = $this->createDependencies();
        $provider = $this->createInstance($dependencies);

        $subscriberOne = new LocalSubscriber();
        $subscriberOne->setEmail('hello@world.tld');
        $subscriberOne->setToken('12345');

        $subscriberTwo = new LocalSubscriber();
        $subscriberTwo->setEmail('foo@bar.tld');

        $group = new Group();
        $group->addSubscriber($subscriberOne);
        $group->addSubscriber($subscriberTwo);

        $newsletter = new Newsletter();
        $newsletter->addGroup($group);

        $receivers = $provider->getReceivers($newsletter);

        $this->assertCount(2, $receivers);

        $this->assertEquals('hello@world.tld', $receivers[0]->getEmail());
        $this->assertEquals(['token' => '12345'], $receivers[0]->getParameters());
        $this->assertEquals('foo@bar.tld', $receivers[1]->getEmail());
    }

    public function testGetTestReceivers()
    {
        $dependencies = $this->createDependencies();
        $provider = $this->createInstance($dependencies);

        $newsletter = new Newsletter();
        $receivers = $provider->getTestReceivers($newsletter);

        $this->assertCount(1, $receivers);

        $this->assertNull($receivers[0]->getEmail());
        $this->assertEquals(['hello' => 'world'], $receivers[0]->getParameters());
        $this->assertEquals('123', $receivers[0]->getToken());
    }
}

class GroupProviderDependency
{
    public $testReceiver;
}
