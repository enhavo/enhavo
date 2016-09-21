<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\Test\TypeTestCase;

class WysiwygTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $config = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Form\Config\WysiwygConfig')
            ->disableOriginalConstructor()
            ->getMock();
        $config->method('getData')->willReturn('config');

        $formType = new WysiwygType($config);
        $form = $this->factory->create($formType);

        $form->setData('old text');
        $form->submit('new text');
        $view = $form->createView();

        $this->assertEquals('new text', $form->getData());
        $this->assertEquals('config', $view->vars['config']);
    }
}