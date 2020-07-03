<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 15:06
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Form\Type;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockCollectionType;
use Enhavo\Bundle\BlockBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\ColumnBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Form\TextBlockMockType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Column;
use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory as FormPreloadExtensionFactory;

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
                'name' => 'column',
                'block' => [
                    'text' => 'Foobar',
                    'column' => [
                        'children' => []
                    ]
                ]
            ],
            1 => [
                'name' => 'text',
                'block' => [
                    'text' => 'Hello World!'
                ]
            ]
        ]);

        $data = $form->getData();

        $this->assertInstanceOf(Node::class, $data[0]);
        $this->assertInstanceOf(Node::class, $data[1]);
    }

    protected function getExtensions()
    {
        return [
            PreloadExtensionFactory::createBlockCollectionTypeExtension($this, [
                'column' => ColumnBlockMockType::class,
                'text' => TextBlockMockType::class
            ]),
            FormPreloadExtensionFactory::createPolyCollectionTypeExtension()
        ];
    }
}
