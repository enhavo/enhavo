<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 15:06
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockCollectionType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory as FormPreloadExtensionFactory;

class BlockCollectionTypeTest extends TypeTestCase
{
    public function testView()
    {
        $form = $this->factory->create(BlockCollectionType::class, null, [
            'prototype_storage' => 'enhavo_block'
        ]);

        $view = $form->createView();
        $this->assertArrayHasKey('multipart', $view->vars);
    }

    protected function getExtensions()
    {
        return [
            PreloadExtensionFactory::createBlockCollectionTypeExtension($this, [TextBlockMockType::class]),
            FormPreloadExtensionFactory::createPolyCollectionTypeExtension()
        ];
    }
}
