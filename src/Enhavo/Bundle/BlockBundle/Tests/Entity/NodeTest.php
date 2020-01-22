<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 14:56
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Entity;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testParents()
    {
        $node = new Node();
        $parent = new Node();

        $node->setParent($parent);

        $parents = $node->getParents();

        $this->assertCount(1, $parents, 'Should have on parent');
        $this->assertTrue($parent === $parents[0], 'Parent should be same object');
    }
}
