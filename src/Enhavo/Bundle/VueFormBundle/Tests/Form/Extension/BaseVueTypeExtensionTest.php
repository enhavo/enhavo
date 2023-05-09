<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\AppBundle\Tests\Mock\TranslatorMock;
use Enhavo\Bundle\VueFormBundle\Form\Extension\BaseVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class BaseVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new BaseVueTypeExtension(new TranslatorMock('_translated')),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();

        $form = $this->factory->create(TextType::class, null, [
            'label' => 'myLabel',
            'attr' => [
                'placeholder' => 'myPlaceholder',
                'title' => 'myTitle',
            ]
        ]);

        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('disabled', $data);
        $this->assertArrayHasKey('attr', $data);
        $this->assertArrayHasKey('rowAttr', $data);
        $this->assertArrayHasKey('labelFormat', $data);

        $this->assertEquals('myLabel_translated', $data['label']);
        $this->assertEquals('myPlaceholder_translated', $data['attr']['placeholder']);
        $this->assertEquals('myTitle_translated', $data['attr']['title']);
    }
}
