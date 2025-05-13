<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Tests\Entity;

use Enhavo\Bundle\NavigationBundle\Entity\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testGetDescendants()
    {
        $grandMother = new Node();
        $daughter = new Node();
        $grandSon = new Node();
        $grandDaughter = new Node();

        $daughter->setParent($grandMother);
        $grandSon->setParent($daughter);
        $grandDaughter->setParent($daughter);

        $descendants = $grandMother->getDescendants();

        $this->assertCount(3, $descendants, 'Should have descendants');
        $this->assertContains($daughter, $descendants, 'Should have this descendant');
        $this->assertContains($grandSon, $descendants, 'Should have this descendant');
        $this->assertContains($grandDaughter, $descendants, 'Should have this descendant');
    }

    public function testSetParent()
    {
        $grandMother = new Node();
        $daughter = new Node();

        $daughter->setParent($grandMother);
        $children = $grandMother->getChildren();

        $this->assertCount(1, $children, 'Should have child');
        $this->assertContains($daughter, $children, 'After setParent, Node should have this child');

        $daughter->setParent(null);
        $this->assertNotContains($daughter, $children, 'After setParent to null, Node shouldn\'t have this child');
    }

    public function testAddChild()
    {
        $grandMother = new Node();
        $daughter = new Node();

        $grandMother->addChild($daughter);

        $this->assertEquals($grandMother, $daughter->getParent(), 'After addChild parent should be set as well');
    }
}
