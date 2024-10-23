<?php

namespace Enhavo\Bundle\MediaBundle\Test\Form\Extension;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\FormBundle\Form\Extension\ListVueTypeExtension;
use Enhavo\Bundle\MediaBundle\Form\Extension\MediaVueTypeExtension;
use Enhavo\Bundle\MediaBundle\Form\Type\FileParametersType;
use Enhavo\Bundle\MediaBundle\Form\Type\FileType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\MediaBundle\Media\ExtensionManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MediaVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions(): array
    {
        $mediaManagerMock = $this->getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();
        $mediaManagerMock->method('getMaxUploadSize')->willReturn(1);

        return [
            new VueTypeExtension(),
            new ListVueTypeExtension(new VueForm()),
            new MediaVueTypeExtension($mediaManagerMock, $this->getMockBuilder(RouterInterface::class)->getMock()),
        ];
    }

    protected function getExtensions(): array
    {
        $extensionManager = $this->getMockBuilder(ExtensionManager::class)->disableOriginalConstructor()->getMock();
        $repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $serializer = $this->getMockBuilder(NormalizerInterface::class)->getMock();
        $formFactory = $this->getMockBuilder(FormFactory::class)->disableOriginalConstructor()->getMock();

        $mediaType = new MediaType($extensionManager, [
            'default_upload_enabled' => true,
        ]);

        $fileType = new FileType($formFactory, $repository, $extensionManager, $serializer, [
            'parameters_type' => FileParametersType::class
        ]);

        return [
            new PreloadedExtension([$mediaType, $fileType], []),
        ];
    }

    public function testViewVars()
    {
        $vueForm = new VueForm();
        $form = $this->factory->create(MediaType::class);
        $data = $vueForm->createData($form->createView());

        $this->assertArrayHasKey('upload', $data);
        $this->assertArrayHasKey('multiple', $data);
        $this->assertArrayHasKey('uploadLabel', $data);
        $this->assertArrayHasKey('buttons', $data);
        $this->assertArrayHasKey('maxUploadSize', $data);
        $this->assertArrayHasKey('editable', $data);

        $this->assertEquals('form-media', $data['component']);
        $this->assertEquals('MediaForm', $data['componentModel']);
        $this->assertEquals('form-media-item', $data['itemComponent']);
    }
}
