<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 17:03
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\ColumnBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Column;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Text;
use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory as FormPreloadExtensionFactory;

class BlockNodeTypeTest extends TypeTestCase
{
    public function testView()
    {
        $node = new Node();
        $form = $this->factory->create(BlockNodeType::class, $node, []);
        $view = $form->createView();

        $this->assertArrayHasKey('multipart', $view->vars);
    }

    protected function getExtensions()
    {
        $textBlock = $this->getMockBuilder(Block::class)->disableOriginalConstructor()->getMock();
        $textBlock->method('getForm')->willReturn(TextBlockMockType::class);
        $textBlock->method('getGroups')->willReturn(['content']);
        $textBlock->method('getModel')->willReturn(Text::class);

        return [
            PreloadExtensionFactory::createBlockCollectionTypeExtension($this, ['text' => $textBlock]),
            FormPreloadExtensionFactory::createPolyCollectionTypeExtension()
        ];
    }
}
