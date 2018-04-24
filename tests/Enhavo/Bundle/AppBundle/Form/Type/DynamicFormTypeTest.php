<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;

class DynamicFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formType = new DynamicFormType();
        $form = $this->factory->create($formType);

        $form->setData([
            0 => '',
            1 => 'B'
        ]);

        $form->submit([
            '0' => 'A',
            '1' => 'B'
        ]);

        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertArraySubset([
            0 => 'A',
            1 => 'B'
        ], $form->getData());
    }
}