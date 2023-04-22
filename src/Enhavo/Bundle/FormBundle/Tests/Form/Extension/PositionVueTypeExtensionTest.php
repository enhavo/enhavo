<?php

namespace Enhavo\Bundle\FormBundle\Test\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Extension\PositionVueTypeExtension;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\VueFormBundle\Form\Extension\ButtonVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Test\TypeTestCase;

class PositionVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new PositionVueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(PositionType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertEquals('PositionForm', $data['componentModel']);
        $this->assertTrue($data['position']);
    }
}
