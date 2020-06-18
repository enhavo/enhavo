<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 17:03
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Symfony\Component\Form\Test\TypeTestCase;

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
        return [
            PreloadExtensionFactory::createBlocksTypeExtension($this, [TextBlockMockType::class])
        ];
    }
}
