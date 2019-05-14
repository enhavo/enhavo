<?php

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;

class WysiwygTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $entrypontLookupMock = $this->getMockBuilder(EntrypointLookupInterface::class)->getMock();
        $entrypontLookupMock->method('getCssFiles')->willReturnCallback(function($message) {
            return ['entrypoint_css'];
        });

        $type = new WysiwygType('entrypoint', $entrypontLookupMock);
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

    public function testEditorCssData()
    {
        $form = $this->factory->create(WysiwygType::class);
        $formView = $form->createView();
        $this->assertEquals('entrypoint_css', $formView->vars['editor_css'][0]);
    }
}
