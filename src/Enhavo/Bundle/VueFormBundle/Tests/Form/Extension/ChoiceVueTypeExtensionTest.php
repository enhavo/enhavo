<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\ResourceBundle\Tests\Mock\TranslatorMock;
use Enhavo\Bundle\VueFormBundle\Form\Extension\ChoiceVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\FormVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ChoiceVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        $normalizer = $this->getMockBuilder(NormalizerInterface::class)->getMock();
        return [
            new VueTypeExtension(),
            new FormVueTypeExtension(new TranslatorMock('_translated'), $normalizer),
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

    public function testChildVarsForExtended()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(ChoiceType::class, null, [
            'choices' => [
                'label1' => 'value1',
                'label2' => 'value2',
            ],
            'expanded' => true,
        ]);

        $view = $form->createView();
        $data = $vueForm->createData($form->createView($view));
        $this->assertEquals('choice[choice]', $data['children'][0]['fullName']);
        $this->assertEquals('choice[choice]', $data['children'][1]['fullName']);
        $this->assertEquals('value1', $data['children'][0]['value']);
        $this->assertEquals('value2', $data['children'][1]['value']);
    }
}
