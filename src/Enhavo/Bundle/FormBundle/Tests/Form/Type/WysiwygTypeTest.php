<?php

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Helper\EntrypointFileManager;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class WysiwygTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $entrypontFileManagerMock = $this->getMockBuilder(EntrypointFileManager::class)->disableOriginalConstructor()->getMock();
        $entrypontFileManagerMock->method('getCssFiles')->willReturn([
            'file1', 'file2'
        ]);

        $type = new WysiwygType('entrypoint', 'build', $entrypontFileManagerMock);
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

//    public function testEditorCssData()
//    {
//        $form = $this->factory->create(WysiwygType::class);
//        $formView = $form->createView();
//        $this->assertEquals('file1', $formView->vars['editor_css'][0]);
//        $this->assertEquals('file2', $formView->vars['editor_css'][1]);
//    }
}
