<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Test\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Extension\PolyCollectionVueTypeExtension;
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PolyCollectionVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        $prototypeManager = $this->getMockBuilder(PrototypeManager::class)->disableOriginalConstructor()->getMock();

        return [
            new VueTypeExtension(),
            new PolyCollectionVueTypeExtension(new VueForm(), $prototypeManager),
        ];
    }

    protected function getExtensions()
    {
        $prototypeManager = $this->getMockBuilder(PrototypeManager::class)->disableOriginalConstructor()->getMock();

        $polyCollectionType = new PolyCollectionType($prototypeManager);

        return [
            new PreloadedExtension([$polyCollectionType], []),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(PolyCollectionType::class, null, [
            'entry_types' => [
                'key1' => TextType::class,
            ],
        ]);

        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('prototypes', $data);
        $this->assertArrayHasKey('allowDelete', $data);
        $this->assertArrayHasKey('allowAdd', $data);
        $this->assertArrayHasKey('entryLabels', $data);
        $this->assertArrayHasKey('sortable', $data);
        $this->assertArrayHasKey('index', $data);
        $this->assertArrayHasKey('prototypeStorage', $data);
        $this->assertArrayHasKey('collapsed', $data);
        $this->assertArrayHasKey('confirmDelete', $data);

        $this->assertEquals('form-poly-collection', $data['component']);
        $this->assertEquals('PolyCollectionForm', $data['componentModel']);
        $this->assertEquals('form-poly-collection-item', $data['itemComponent']);
    }
}
