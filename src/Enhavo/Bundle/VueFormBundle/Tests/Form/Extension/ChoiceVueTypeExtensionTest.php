<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\AppBundle\Tests\Mock\TranslatorMock;
use Enhavo\Bundle\VueFormBundle\Form\Extension\ChoiceVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Test\TypeTestCase;

class ChoiceVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new ChoiceVueTypeExtension(new TranslatorMock('_translated')),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(ChoiceType::class, null, [
            'choices' => [
                'label1' => 'value1',
                'label2' => 'value2',
            ],
        ]);
        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('expanded', $data);
        $this->assertArrayHasKey('multiple', $data);
        $this->assertArrayHasKey('choices', $data);
        $this->assertArrayHasKey('placeholder', $data);
        $this->assertArrayHasKey('placeholderInChoices', $data);
        $this->assertArrayHasKey('preferredChoices', $data);
        $this->assertArrayHasKey('separator', $data);

        $this->assertEquals('label1_translated', $data['choices'][0]['label']);
    }
}
