<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 15:06
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\BlocksType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeStorage;
use Symfony\Component\Form\Test\TypeTestCase;

class BlocksTypeTest extends TypeTestCase
{
    public function testView()
    {
        $storage = new PrototypeStorage();
        $form = $this->factory->create(BlocksType::class, null, [
            'prototype_storage' => $storage
        ]);
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
