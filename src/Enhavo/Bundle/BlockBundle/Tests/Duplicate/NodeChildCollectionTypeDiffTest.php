<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Duplicate;

use Enhavo\Bundle\BlockBundle\Duplicate\NodeChildCollectionType;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Change;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\ModelDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\ResourceBundle\Tests\Duplicate\DiffTestTrait;
use PHPUnit\Framework\TestCase;

class NodeChildCollectionTypeDiffTest extends TestCase
{
    use DiffTestTrait;

    private function createFactory()
    {
        $duplicateFactory = $this->getMockBuilder(DuplicateFactory::class)->disableOriginalConstructor()->getMock();

        return $this->createDiffFactory([
            new PropertyDuplicateType(),
            new ModelDuplicateType($duplicateFactory),
            new NodeChildCollectionType($duplicateFactory),
        ], [
            DiffPage::class => [
                'content' => [['type' => 'model', 'groups' => ['revision']]],
            ],
            Node::class => [
                'children' => [['type' => NodeChildCollectionType::class, 'groups' => ['revision']]],
                'block' => [['type' => 'model', 'groups' => ['revision']]],
            ],
            DiffTextBlock::class => [
                'text' => [['type' => 'property', 'groups' => ['revision']]],
            ],
            DiffColumnBlock::class => [
                'column' => [['type' => 'model', 'groups' => ['revision']]],
            ],
        ]);
    }

    private function createRoot(array $blocks): Node
    {
        $root = new Node();
        $root->setType(NodeInterface::TYPE_ROOT);
        foreach ($blocks as $position => [$name, $block]) {
            $node = new Node();
            $node->setName($name);
            $node->setPosition($position);
            $node->setBlock($block);
            $root->addChild($node);
        }

        return $root;
    }

    /**
     * @param Change[] $changes
     */
    private function normalize(array $changes): array
    {
        return array_map(fn (Change $change) => [
            $change->getSection() ? sprintf('%d %s %s', $change->getSection()->getPosition(), $change->getSection()->getLabel(), $change->getSection()->getStatus()) : null,
            $change->getField(),
            $change->getOld(),
            $change->getNew(),
        ], $changes);
    }

    public function testAlignedSections()
    {
        $old = new DiffPage($this->createRoot([
            ['text', new DiffTextBlock('Hello')],
            ['image', new DiffTextBlock(null)],
            ['quote', new DiffTextBlock('Gone')],
        ]));
        $new = new DiffPage($this->createRoot([
            ['text', new DiffTextBlock('Hello world')],
            ['picture_text', new DiffTextBlock(null)],
            ['image', new DiffTextBlock(null)],
        ]));

        $diff = $this->createFactory()->diff($old, $new, ['groups' => 'revision']);

        // an inserted block doesn't shift the following ones, an empty added block still shows up
        $this->assertEquals([
            ['1 Text changed', 'Text', 'Hello', 'Hello world'],
            ['2 Picture text added', null, null, null],
            ['3 Quote removed', 'Text', 'Gone', null],
        ], $this->normalize($diff->getChanges()));
    }

    public function testNestedContentByPosition()
    {
        $old = new DiffPage($this->createRoot([
            ['column', new DiffColumnBlock($this->createRoot([['text', new DiffTextBlock('Left')]]))],
        ]));
        $new = new DiffPage($this->createRoot([
            ['column', new DiffColumnBlock($this->createRoot([['text', new DiffTextBlock('Right')]]))],
        ]));

        $diff = $this->createFactory()->diff($old, $new, ['groups' => 'revision']);

        $this->assertEquals([
            ['1 Column changed', 'Column › 1. Text › Text', 'Left', 'Right'],
        ], $this->normalize($diff->getChanges()));
    }
}

class DiffPage
{
    public function __construct(
        public ?Node $content = null,
    ) {
    }
}

class DiffTextBlock implements BlockInterface
{
    private ?NodeInterface $node = null;

    public function __construct(
        public ?string $text = null,
    ) {
    }

    public function getNode()
    {
        return $this->node;
    }

    public function setNode(?NodeInterface $node)
    {
        $this->node = $node;
    }
}

class DiffColumnBlock implements BlockInterface
{
    private ?NodeInterface $node = null;

    public function __construct(
        public ?Node $column = null,
    ) {
    }

    public function getNode()
    {
        return $this->node;
    }

    public function setNode(?NodeInterface $node)
    {
        $this->node = $node;
    }
}
