<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class WysiwygTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $type = new WysiwygType();
        return array(
            new PreloadedExtension(array($type), array()),
        );
    }

    public function testSubmitValidData()
    {
        $form = $this->factory->create(WysiwygType::class);

        $form->setData('old text');
        $form->submit('new text');
        $this->assertEquals('new text', $form->getData());
    }
}
