<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 00:00
 */

namespace Enhavo\Bundle\TemplateBundle\Tests\Entity;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testSetContent()
    {
        $template = new Template();
        $content = new Node();

        $template->setContent($content);

        $this->assertEquals(NodeInterface::TYPE_ROOT, $content->getType(), 'After setContent, the content type should be root');
    }
}
