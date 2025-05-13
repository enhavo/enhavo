<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Tests\Entity;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    public function testSetContent()
    {
        $appointment = new Appointment();
        $content = new Node();

        $appointment->setContent($content);

        $this->assertEquals(NodeInterface::TYPE_ROOT, $content->getType(), 'After setContent, the content type should be root');
    }
}
