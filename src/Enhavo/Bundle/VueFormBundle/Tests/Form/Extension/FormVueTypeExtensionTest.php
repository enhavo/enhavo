<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\AppBundle\Tests\Mock\TranslatorMock;
use Enhavo\Bundle\VueFormBundle\Form\Extension\FormVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        $normalizerMock = $this->getMockBuilder(NormalizerInterface::class)->getMock();
        $normalizerMock->method('normalize')->willReturn('normalizedValue');


        return [
            new VueTypeExtension(),
            new FormVueTypeExtension(new TranslatorMock('_translated'), $normalizerMock),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(TextType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('value', $data);
        $this->assertArrayHasKey('compound', $data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('required', $data);
        $this->assertArrayHasKey('fullName', $data);
        $this->assertArrayHasKey('size', $data);
        $this->assertArrayHasKey('help', $data);
        $this->assertArrayHasKey('helpAttr', $data);
        $this->assertArrayHasKey('helpHtml', $data);

        $this->assertEquals('form-row', $data['rowComponent']);
    }
}
