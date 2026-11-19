<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\FrameworkBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaLibraryBundle\Endpoint\MediaLibraryFileUploadEndpointType;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Entity\Item;
use Enhavo\Bundle\MediaLibraryBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpFoundation\Request;

class MediaLibraryFileUploadEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private function createDependencies(): MediaLibraryFileUploadEndpointTypeDependencies
    {
        $tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $tokenGenerator->method('generateToken')->willReturn('library-token');
        $checksumGenerator = $this->getMockBuilder(ChecksumGeneratorInterface::class)->getMock();
        $checksumGenerator->method('getChecksum')->willReturn('__CHECKSUM__');

        $dependencies = new MediaLibraryFileUploadEndpointTypeDependencies();
        $dependencies->fileFactory = new FileFactory(File::class, $tokenGenerator, $checksumGenerator, new MockHttpClient());
        $dependencies->itemRepository = $this->getMockBuilder(ItemRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->mediaLibraryManager = $this->getMockBuilder(MediaLibraryManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(MediaLibraryFileUploadEndpointTypeDependencies $dependencies): MediaLibraryFileUploadEndpointType
    {
        $instance = new MediaLibraryFileUploadEndpointType(
            $dependencies->fileFactory,
            $dependencies->itemRepository,
            $dependencies->mediaLibraryManager,
            $dependencies->resourceManager,
        );
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function createFieldFile(): File
    {
        $file = new File();
        $file->setBasename('image.txt');
        $file->setMimeType('text/plain');
        $file->setChecksum('__CHECKSUM__');
        $file->setToken('field-token');
        $file->setContent(new Content('content'));
        $file->setGarbage(true);

        return $file;
    }

    private function handle(MediaLibraryFileUploadEndpointType $instance, array $files): void
    {
        $request = new Request();
        $context = new Context($request);
        $context->set('files', $files);

        $instance->handleRequest([], $request, new Data(), $context);
    }

    public function testUploadCreatesLibraryItemAndLinksFieldFile()
    {
        $dependencies = $this->createDependencies();
        $dependencies->itemRepository->method('findByChecksum')->with('__CHECKSUM__')->willReturn([]);

        $item = new Item();
        $dependencies->mediaLibraryManager->expects($this->once())->method('createItem')
            ->willReturnCallback(function (File $libraryFile) use ($item) {
                $item->setFile($libraryFile);

                return $item;
            });

        $saved = [];
        $dependencies->resourceManager->expects($this->exactly(2))->method('save')
            ->willReturnCallback(function ($resource) use (&$saved) {
                $saved[] = $resource;
            });

        $fieldFile = $this->createFieldFile();
        $this->handle($this->createInstance($dependencies), [$fieldFile]);

        $this->assertSame([$item, $fieldFile], $saved);
        $this->assertNotSame($fieldFile, $item->getFile());
        $this->assertSame($item, $fieldFile->getItem());
        $this->assertTrue($fieldFile->isGarbage());
    }

    public function testUploadReusesItemWithSameChecksum()
    {
        $dependencies = $this->createDependencies();

        $existingItem = new Item();
        $dependencies->itemRepository->method('findByChecksum')->with('__CHECKSUM__')->willReturn([$existingItem]);
        $dependencies->mediaLibraryManager->expects($this->never())->method('createItem');

        $fieldFile = $this->createFieldFile();
        $dependencies->resourceManager->expects($this->once())->method('save')->with($fieldFile);

        $this->handle($this->createInstance($dependencies), [$fieldFile]);

        $this->assertSame($existingItem, $fieldFile->getItem());
        $this->assertTrue($fieldFile->isGarbage());
    }

    public function testNoStoredFilesDoesNothing()
    {
        $dependencies = $this->createDependencies();
        $dependencies->itemRepository->expects($this->never())->method('findByChecksum');
        $dependencies->resourceManager->expects($this->never())->method('save');

        $this->handle($this->createInstance($dependencies), []);
    }
}

class MediaLibraryFileUploadEndpointTypeDependencies
{
    public FileFactory $fileFactory;
    public ItemRepository|MockObject $itemRepository;
    public MediaLibraryManager|MockObject $mediaLibraryManager;
    public ResourceManager|MockObject $resourceManager;
}