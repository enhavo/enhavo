<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Tests\Entity;

use Enhavo\Bundle\PageBundle\Entity\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testGetDescendants()
    {
        $grandMother = new Page();
        $daughter = new Page();
        $grandSon = new Page();
        $grandDaughter = new Page();

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
        $grandMother = new Page();
        $daughter = new Page();

        $daughter->setParent($grandMother);
        $children = $grandMother->getChildren();

        $this->assertCount(1, $children, 'Should have child');
        $this->assertContains($daughter, $children, 'After setParent, Node should have this child');
    }

    public function testAddChild()
    {
        $grandMother = new Page();
        $daughter = new Page();

        $grandMother->addChild($daughter);

        $this->assertEquals($grandMother, $daughter->getParent(), 'After addChild parent should be set as well');
    }

    public function testGetParents()
    {
        $grandMother = new Page();
        $mother = new Page();
        $child = new Page();

        $mother->setParent($grandMother);
        $child->setParent($mother);

        $parents = $child->getParents();

        $this->assertCount(2, $parents, 'Should have two parents');
        $this->assertEquals($mother, $parents[0]);
        $this->assertEquals($grandMother, $parents[1]);
    }
}
