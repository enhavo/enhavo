<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Duplicate\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\CollectionDuplicateType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CollectionDuplicateTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new CollectionDuplicateTypeDependencies();
        $dependencies->duplicateFactory = $this->getMockBuilder(DuplicateFactory::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(CollectionDuplicateTypeDependencies $dependencies)
    {
        $instance = new CollectionDuplicateType(
            $dependencies->duplicateFactory,
        );

        return $instance;
    }

    public function testDuplicateByReference()
    {
        $dependencies = $this->createDependencies();

        $targetChildItemOne = new SourceItem();
        $targetChildItemTwo = new SourceItem();
        $duplicateStack = [];
        $duplicateStack[] = $targetChildItemOne;
        $duplicateStack[] = $targetChildItemTwo;
        $targetItem = new SourceItem();

        $sourceItem = new SourceItem();
        $sourceChildItemOne = new SourceItem();
        $sourceChildItemTwo = new SourceItem();
        $sourceItem->addChild($sourceChildItemOne);
        $sourceItem->addChild($sourceChildItemTwo);

        $dependencies->duplicateFactory->method('duplicate')->willReturnCallback(function () use (&$duplicateStack) {
            return array_pop($duplicateStack);
        });

        $instance = $this->createInstance($dependencies);

        $sourceValue = new SourceValue($sourceItem->children, $sourceItem, 'children');
        $targetValue = new TargetValue(null, $targetItem, 'children');

        $instance->duplicate([
            'by_reference' => true,
            'groups' => 'test',
        ], $sourceValue, $targetValue, ['groups' => 'test']);

        $this->assertEquals($targetItem, $targetChildItemOne->parent);
        $this->assertEquals($targetItem, $targetChildItemTwo->parent);
        $this->assertEquals(2, count($targetItem->children));
    }
}

class CollectionDuplicateTypeDependencies
{
    public DuplicateFactory|MockObject $duplicateFactory;
}

class SourceItem
{
    public Collection $children;
    public ?SourceItem $parent = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function addChild(SourceItem $child): void
    {
        $this->children->add($child);
        $child->parent = $this;
    }

    public function removeChild(SourceItem $child): void
    {
        $this->children->removeElement($child);
        $child->parent = null;
    }
}
