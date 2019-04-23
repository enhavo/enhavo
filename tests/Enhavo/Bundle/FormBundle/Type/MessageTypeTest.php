<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class MessageTypeTest extends TypeTestCase
{
    private $translator;

    protected function setUp()
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->translator->method('trans')->willReturnCallback(function($id) {
            return $id;
        });
        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new MessageType($this->translator);
        return array(
            new PreloadedExtension(array($type), array()),
        );
    }

    protected function createForm($options = [])
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $translator->method('trans')->willReturnCallback(function($message) {
            return $message;
        });

        $form = $this->factory->create(MessageType::class, null, $options);

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
        $this->assertEquals('testMessage', $formView->vars['message']);
        $this->assertEquals(MessageType::MESSAGE_TYPE_WARNING, $formView->vars['type']);
    }
}
