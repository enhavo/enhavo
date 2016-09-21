<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\ListType;
use Symfony\Component\Form\Test\TypeTestCase;

class ListTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formType = new ListType();
        $form = $this->factory->create($formType);

        $form->setData([
            0 => 'A',
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

    public function testOrderOfArray()
    {
        $formType = new ListType();
        $form = $this->factory->create($formType, null);

        $form->setData([
            0 => 'A',
            1 => 'B'
        ]);

        $form->submit([
            '1' => 'B',
            '0' => 'A',
        ]);

        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertArraySubset([
            0 => 'B',
            1 => 'A'
        ], $form->getData());
    }
}