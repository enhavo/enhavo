<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
