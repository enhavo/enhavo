<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 16:33
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
