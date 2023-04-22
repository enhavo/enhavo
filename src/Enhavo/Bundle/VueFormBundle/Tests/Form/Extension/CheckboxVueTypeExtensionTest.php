<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\Extension\CheckboxVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Test\TypeTestCase;

class CheckboxVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new CheckboxVueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(CheckboxType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('checked', $data);
        $this->assertEquals('form-checkbox', $data['component']);
        $this->assertEquals('FormCheckbox', $data['componentModel']);
    }
}
