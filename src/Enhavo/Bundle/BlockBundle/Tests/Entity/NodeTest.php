<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
