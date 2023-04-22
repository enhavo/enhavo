<?php

namespace Enhavo\Bundle\FormBundle\Test\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Extension\WysiwygVueTypeExtension;
use Enhavo\Bundle\FormBundle\Form\Helper\EntrypointFileManager;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class WysiwygVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions()
    {
        return [
            new VueTypeExtension(),
            new WysiwygVueTypeExtension(),
        ];
    }

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

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(WysiwygType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertEquals('form-wysiwyg', $data['component']);
        $this->assertEquals('WysiwygForm', $data['componentModel']);
    }
}
