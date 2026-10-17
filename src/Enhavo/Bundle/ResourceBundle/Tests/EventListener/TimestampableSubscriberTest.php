<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\EventListener;

use Enhavo\Bundle\FrameworkBundle\Entity\TimestampableTrait;
use Enhavo\Bundle\FrameworkBundle\Model\Timestampable;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\EventListener\TimestampableSubscriber;
use PHPUnit\Framework\TestCase;

class TimestampableSubscriberTest extends TestCase
{
    public function testSetTimestamps()
    {
        $resource = new TimestampableResource();

        (new TimestampableSubscriber())->preSave(new ResourceEvent($resource));

        $this->assertNotNull($resource->createdAt);
        $this->assertSame($resource->createdAt, $resource->updatedAt);
    }

    public function testKeepCreatedAt()
    {
        $resource = new TimestampableResource();
        $resource->createdAt = new \DateTime('2020-01-01');

        (new TimestampableSubscriber())->preSave(new ResourceEvent($resource));

        $this->assertEquals('2020-01-01', $resource->createdAt->format('Y-m-d'));
        $this->assertNotNull($resource->updatedAt);
    }

    public function testIgnoreOtherResources()
    {
        $resource = new \stdClass();

        (new TimestampableSubscriber())->preSave(new ResourceEvent($resource));

        $this->assertObjectNotHasProperty('updatedAt', $resource);
    }
}

class TimestampableResource implements Timestampable
{
    use TimestampableTrait;
}
