<?php

namespace Enhavo\Bundle\FormBundle\Test\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Extension\ListVueTypeExtension;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class ListVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new ListVueTypeExtension(new VueForm()),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(ListType::class, null, [
            'entry_type' => TextType::class,
        ]);
        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('border', $data);
        $this->assertArrayHasKey('sortable', $data);
        $this->assertArrayHasKey('allowDelete', $data);
        $this->assertArrayHasKey('allowAdd', $data);
        $this->assertArrayHasKey('blockName', $data);
        $this->assertArrayHasKey('prototype', $data);
        $this->assertArrayHasKey('prototypeName', $data);
        $this->assertArrayHasKey('index', $data);
        $this->assertArrayHasKey('draggableGroup', $data);
        $this->assertArrayHasKey('draggableHandle', $data);
        $this->assertArrayHasKey('onDelete', $data);

        $this->assertEquals('form-list', $data['component']);
        $this->assertEquals('ListForm', $data['componentModel']);
        $this->assertEquals('form-list-item', $data['itemComponent']);
    }
}
