<?php

namespace Enhavo\Bundle\MediaBundle\Test\Form\Extension;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\FormBundle\Form\Extension\ListVueTypeExtension;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Form\Extension\MediaVueTypeExtension;
use Enhavo\Bundle\MediaBundle\Form\Type\FileParametersType;
use Enhavo\Bundle\MediaBundle\Form\Type\FileType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaVueTypeExtensionTest extends TypeTestCase
{
    protected function getTypeExtensions(): array
    {
        $mediaManagerMock = $this->getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();
        $mediaManagerMock->method('getMaxUploadSize')->willReturn(1);

        return [
            new VueTypeExtension(),
            new ListVueTypeExtension(new VueForm()),
            new MediaVueTypeExtension(
                $mediaManagerMock,
                $this->getMockBuilder(RouterInterface::class)->getMock(),
                $this->getMockBuilder(TranslatorInterface::class)->getMock(),
            ),
        ];
    }

    protected function getExtensions(): array
    {
        $actionManager = $this->getMockBuilder(ActionManager::class)->disableOriginalConstructor()->getMock();
        $repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $serializer = $this->getMockBuilder(NormalizerInterface::class)->getMock();
        $formFactory = $this->getMockBuilder(FormFactory::class)->disableOriginalConstructor()->getMock();

        $formConfiguration = [
            'default' => [
                'parameters_type' => FileParametersType::class,
                'parameters_options' => [],
                'actions' => [],
                'actions_file' => [],
                'route' => 'some_route',
                'upload' => true,
            ],
        ];

        $mediaType = new MediaType($actionManager, $formConfiguration);
        $fileType = new FileType($formFactory, $repository, $serializer, $formConfiguration, File::class, $actionManager);

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
        $this->assertArrayHasKey('maxUploadSize', $data);
        $this->assertArrayHasKey('editable', $data);

        $this->assertEquals('form-media', $data['component']);
        $this->assertEquals('MediaForm', $data['componentModel']);
        $this->assertEquals('form-media-item', $data['itemComponent']);
    }
}
