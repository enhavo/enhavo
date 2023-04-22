<?php

namespace Enhavo\Bundle\VueFormBundle\Test\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\Extension\SubmitVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class SubmitVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new SubmitVueTypeExtension(),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(SubmitType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertEquals('form-submit', $data['component']);
    }
}
