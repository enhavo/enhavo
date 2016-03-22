<?php

namespace Enhavo\Bundle\AppBundle\Tests\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Test\TypeTestCase;

class BooleanTypeTest extends TypeTestCase
{
    protected function createForm($options = [])
    {
        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();
        $translator->method('trans')->willReturn('translation');

        $formType = new BooleanType($translator);
        $form = $this->factory->create($formType, null, $options);

        return $form;
    }

    public function testSubmitValidDataToTrue()
    {
        $form = $this->createForm();

        $form->setData(false);
        $form->submit('true');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertTrue(true === $form->getData());
    }

    public function testSubmitValidDataToFalse()
    {
        $form = $this->createForm();

        $form->setData(true);
        $form->submit('false');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertTrue(false === $form->getData());
    }

    public function testDefaultOptionValueWithTrue()
    {
        $form = $this->createForm(['default' => true]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_TRUE, $view->vars['value']);
    }

    public function testDefaultOptionValueWithFalse()
    {
        $form = $this->createForm(['default' => false]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_FALSE, $view->vars['value']);
    }

    public function testDefaultOptionValueWithNull()
    {
        $form = $this->createForm(['default' => null]);
        $view = $form->createView();
        $this->assertEquals(BooleanType::VALUE_NULL, $view->vars['value']);
    }
}