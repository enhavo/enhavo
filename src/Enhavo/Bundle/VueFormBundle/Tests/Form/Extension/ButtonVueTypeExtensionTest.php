<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\Extension\ButtonVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Test\TypeTestCase;

class ButtonVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new ButtonVueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(ButtonType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertEquals('form-button', $data['component']);
        $this->assertEquals('form-button-row', $data['rowComponent']);
    }
}
