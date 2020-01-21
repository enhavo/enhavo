<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 19:49
 */

namespace Enhavo\Bundle\SidebarBundle\Tests\Entity;


use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\SidebarBundle\Entity\Sidebar;
use PHPUnit\Framework\TestCase;

class SidebarTest extends TestCase
{
    public function testSetContent()
    {
        $appointment = new Sidebar();
        $content = new Node();

        $appointment->setContent($content);

        $this->assertEquals(NodeInterface::TYPE_ROOT, $content->getType(), 'After setContent, the content type should be root');
    }
}
