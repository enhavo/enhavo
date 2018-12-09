<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\AppBundle\Form\Type\PositionType;
use PHPUnit\Framework\TestCase;

class PositionTypeTest extends TestCase
{
    protected function createForm($options = [])
    {
        $formType = new PositionType();
        $form = $this->factory->create($formType, null, $options);
        return $form;
    }

    public function testSubmitValidData()
    {
        $form = $this->createForm();

        $form->setData('');
        $form->submit('1');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }
}