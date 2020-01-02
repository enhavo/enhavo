<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;

class PositionTypeTest extends TypeTestCase
{
    protected function createForm($options = [])
    {
        $form = $this->factory->create(PositionType::class, null, $options);
        return $form;
    }

    public function testSubmitValidData()
    {
        $form = $this->createForm();

        $form->setData('');
        $form->submit('1');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
    }
}
