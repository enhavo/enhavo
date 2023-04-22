<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\Extension\TextareaVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class TextareaVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new TextareaVueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(TextareaType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertEquals('form-textarea', $data['component']);
    }
}
