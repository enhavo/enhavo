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
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaLibraryFileUploadEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private ?string $path = null;

    protected function tearDown(): void
    {
        if (null !== $this->path && file_exists($this->path)) {
            unlink($this->path);
        }
    }

    private function createDependencies(): MediaLibraryFileUploadEndpointTypeDependencies
    {
        $tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $tokenGenerator->method('generateToken')->willReturnOnConsecutiveCalls('token-1', 'token-2');
        $checksumGenerator = $this->getMockBuilder(ChecksumGeneratorInterface::class)->getMock();
        $checksumGenerator->method('getChecksum')->willReturn('__CHECKSUM__');

        $dependencies = new MediaLibraryFileUploadEndpointTypeDependencies();
        $dependencies->fileFactory = new FileFactory(File::class, $tokenGenerator, $checksumGenerator, new MockHttpClient());
        $dependencies->itemRepository = $this->getMockBuilder(ItemRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->mediaLibraryManager = $this->getMockBuilder(MediaLibraryManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->validator->method('validate')->willReturn(new ConstraintViolationList());

        return $dependencies;
    }

    private function createInstance(MediaLibraryFileUploadEndpointTypeDependencies $dependencies): MediaLibraryFileUploadEndpointType
    {
        $instance = new MediaLibraryFileUploadEndpointType(
            $dependencies->fileFactory,
            $dependencies->itemRepository,
            $dependencies->mediaLibraryManager,
            $dependencies->resourceManager,
            $dependencies->validator,
        );
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function createRequest(): Request
    {
        $this->path = tempnam(sys_get_temp_dir(), 'enhavo_upload');
        file_put_contents($this->path, 'content');

        return new Request([], [], [], [], [
            'files' => [new UploadedFile($this->path, 'image.txt', 'text/plain', null, true)],
        ]);
    }

    private function handle(MediaLibraryFileUploadEndpointType $instance, Request $request): Context
    {
        $context = new Context($request);
        $instance->handleRequest(['validation_groups' => ['media_upload']], $request, new Data(), $context);

        return $context;
    }

    public function testUploadCreatesLibraryItemAndLinkedFieldFile()
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

        $context = $this->handle($this->createInstance($dependencies), $this->createRequest());

        $this->assertSame($item, $saved[0]);
        $fieldFile = $saved[1];
        $this->assertInstanceOf(File::class, $fieldFile);
        $this->assertNotSame($item->getFile(), $fieldFile);
        $this->assertSame($item, $fieldFile->getItem());
        $this->assertTrue($fieldFile->isGarbage());

        $response = json_decode($context->getResponse()->getContent(), true);
        $this->assertCount(1, $response);
        $this->assertEquals('token-2', $response[0]['token']);
        $this->assertEquals('image.txt', $response[0]['basename']);
    }

    public function testUploadReusesItemWithSameChecksum()
    {
        $dependencies = $this->createDependencies();

        $existingFile = new File();
        $existingFile->setBasename('existing.txt');
        $existingFile->setMimeType('text/plain');
        $existingFile->setChecksum('__CHECKSUM__');
        $existingFile->setToken('existing-token');
        $existingFile->setContent($this->createMock(ContentInterface::class));
        $existingItem = new Item();
        $existingItem->setFile($existingFile);

        $dependencies->itemRepository->method('findByChecksum')->with('__CHECKSUM__')->willReturn([$existingItem]);
        $dependencies->mediaLibraryManager->expects($this->never())->method('createItem');

        $saved = [];
        $dependencies->resourceManager->expects($this->once())->method('save')
            ->willReturnCallback(function ($resource) use (&$saved) {
                $saved[] = $resource;
            });

        $this->handle($this->createInstance($dependencies), $this->createRequest());

        $fieldFile = $saved[0];
        $this->assertInstanceOf(File::class, $fieldFile);
        $this->assertSame($existingItem, $fieldFile->getItem());
        $this->assertEquals('existing.txt', $fieldFile->getBasename());
        $this->assertTrue($fieldFile->isGarbage());
    }

    public function testInvalidUploadReturnsErrorsAndSavesNothing()
    {
        $dependencies = $this->createDependencies();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->validator->method('validate')->willReturn(new ConstraintViolationList([
            new ConstraintViolation('File too large', null, [], null, 'file', null),
        ]));
        $dependencies->resourceManager->expects($this->never())->method('save');
        $dependencies->mediaLibraryManager->expects($this->never())->method('createItem');

        $context = $this->handle($this->createInstance($dependencies), $this->createRequest());

        $this->assertEquals(400, $context->getResponse()->getStatusCode());
        $this->assertEquals(['success' => false, 'errors' => ['File too large']], json_decode($context->getResponse()->getContent(), true));
    }
}

class MediaLibraryFileUploadEndpointTypeDependencies
{
    public FileFactory $fileFactory;
    public ItemRepository|MockObject $itemRepository;
    public MediaLibraryManager|MockObject $mediaLibraryManager;
    public ResourceManager|MockObject $resourceManager;
    public ValidatorInterface|MockObject $validator;
}
