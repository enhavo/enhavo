<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class WysiwygTypeTest extends TypeTestCase
{
    private $config;

    protected function setUp()
    {
        $this->config = $this->getMockBuilder('Enhavo\Bundle\FormBundle\Form\Config\WysiwygConfig')
            ->disableOriginalConstructor()
            ->getMock();
        $this->config->method('getData')->willReturn('config');
        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new WysiwygType($this->config);
        return array(
            new PreloadedExtension(array($type), array()),
        );
    }

    public function testSubmitValidData()
    {
        $form = $this->factory->create(WysiwygType::class);

        $form->setData('old text');
        $form->submit('new text');
        $view = $form->createView();

        $this->assertEquals('new text', $form->getData());
        $this->assertEquals('config', $view->vars['config']);
    }
}
