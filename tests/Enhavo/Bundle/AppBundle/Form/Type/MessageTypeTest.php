<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\MessageType;
use Symfony\Component\Form\Test\TypeTestCase;
use PHPUnit\Framework\TestCase;

class MessageTypeTest extends TestCase
{
    protected function createForm($options = [])
    {
        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();
        $translator->method('trans')->willReturnCallback(function($message) {
            return $message;
        });

        $formType = new MessageType($translator);
        $form = $this->factory->create($formType, null, $options);

        return $form;
    }

    public function testSubmitWithMessage()
    {
        $form = $this->createForm([
            'message' => 'testMessage'
        ]);

        $form->setData(false);
        $form->submit([]);
        $formView = $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals('testMessage', $formView->vars['message']);
        $this->assertEquals(MessageType::MESSAGE_TYPE_INFO, $formView->vars['type']);
    }

    public function testSubmitWithTypeWarning()
    {
        $form = $this->createForm([
            'message' => 'testMessage',
            'type' => MessageType::MESSAGE_TYPE_WARNING
        ]);

        $form->setData(false);
        $form->submit([]);
        $formView = $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals('testMessage', $formView->vars['message']);
        $this->assertEquals(MessageType::MESSAGE_TYPE_WARNING, $formView->vars['type']);
    }
}