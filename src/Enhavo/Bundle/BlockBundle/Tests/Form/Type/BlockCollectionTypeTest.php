<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockCollectionType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\ColumnBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Column;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Text;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory as FormPreloadExtensionFactory;
use Symfony\Component\Form\Test\TypeTestCase;

class BlockCollectionTypeTest extends TypeTestCase
{
    public function testView()
    {
        $form = $this->factory->create(BlockCollectionType::class, null);
        $view = $form->createView();
        $this->assertArrayHasKey('multipart', $view->vars);
    }

    public function testSubmit()
    {
        $form = $this->factory->create(BlockCollectionType::class, null);

        $form->submit([
            0 => [
                'uuid' => '8758e476-0696-46d0-8733-752d6d180a1e',
                'name' => 'column',
                'block' => [
                    'text' => 'Foobar',
                    'column' => [
                        'children' => [],
                    ],
                ],
            ],
            1 => [
                'uuid' => 'f4ce058f-7097-4644-9d4e-3dacf8e99538',
                'name' => 'text',
                'block' => [
                    'text' => 'Hello World!',
                ],
            ],
        ]);

        $data = $form->getData();

        $this->assertInstanceOf(Node::class, $data[0]);
        $this->assertInstanceOf(Node::class, $data[1]);
    }

    public function testGroupOption()
    {
        $form = $this->factory->create(BlockCollectionType::class, null, [
            'item_groups' => ['content'],
        ]);

        $view = $form->createView();

        $this->assertEquals(['text'], $view->vars['poly_collection_config']['entryKeys']);
    }

    public function testItemOption()
    {
        $form = $this->factory->create(BlockCollectionType::class, null, [
            'items' => ['text'],
        ]);

        $view = $form->createView();

        $this->assertEquals(['text'], $view->vars['poly_collection_config']['entryKeys']);
    }

    protected function getExtensions()
    {
        $columnBlock = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $columnBlock->method('getForm')->willReturn(ColumnBlockMockType::class);
        $columnBlock->method('getGroups')->willReturn(['layout']);
        $columnBlock->method('getModel')->willReturn(Column::class);

        $textBlock = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $textBlock->method('getForm')->willReturn(TextBlockMockType::class);
        $textBlock->method('getGroups')->willReturn(['content']);
        $textBlock->method('getModel')->willReturn(Text::class);

        return [
            PreloadExtensionFactory::createBlockCollectionTypeExtension($this, [
                'column' => $columnBlock,
                'text' => $textBlock,
            ]),
            FormPreloadExtensionFactory::createPolyCollectionTypeExtension(),
        ];
    }
}
