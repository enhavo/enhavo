<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Tests\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\FrameworkBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Endpoint\Type\FileUploadEndpointType;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileUploadEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    private ?string $path = null;

    protected function tearDown(): void
    {
        if (null !== $this->path && file_exists($this->path)) {
            unlink($this->path);
        }
    }

    private function createDependencies(): FileUploadEndpointTypeDependencies
    {
        $tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $tokenGenerator->method('generateToken')->willReturn('token');
        $checksumGenerator = $this->getMockBuilder(ChecksumGeneratorInterface::class)->getMock();
        $checksumGenerator->method('getChecksum')->willReturn('__CHECKSUM__');

        $dependencies = new FileUploadEndpointTypeDependencies();
        $dependencies->fileFactory = new FileFactory(File::class, $tokenGenerator, $checksumGenerator, new MockHttpClient());
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->validator->method('validate')->willReturn(new ConstraintViolationList());

        return $dependencies;
    }

    private function createInstance(FileUploadEndpointTypeDependencies $dependencies): FileUploadEndpointType
    {
        $instance = new FileUploadEndpointType(
            $dependencies->fileFactory,
            $dependencies->resourceManager,
            $dependencies->validator,
        );
        $this->enhanceEndpoint($instance);

        return $instance;
    }

    private function handle(FileUploadEndpointType $instance, bool $garbage = true): Context
    {
        $this->path = tempnam(sys_get_temp_dir(), 'enhavo_upload');
        file_put_contents($this->path, 'content');

        $request = new Request([], [], [], [], [
            'files' => [new UploadedFile($this->path, 'image.txt', 'text/plain', null, true)],
        ]);
        $context = new Context($request);

        $instance->handleRequest(['garbage' => $garbage, 'validation_groups' => ['media_upload']], $request, new Data(), $context);

        return $context;
    }

    public function testUploadStoresFileAndLeavesContextRunningForChildTypes()
    {
        $dependencies = $this->createDependencies();
        $dependencies->resourceManager->expects($this->once())->method('save');

        $context = $this->handle($this->createInstance($dependencies));

        $this->assertFalse($context->isStopped());
        $this->assertCount(1, $context->get('files'));
        $this->assertTrue($context->get('files')[0]->isGarbage());

        $response = json_decode($context->getResponse()->getContent(), true);
        $this->assertEquals(200, $context->getResponse()->getStatusCode());
        $this->assertCount(1, $response);
        $this->assertEquals('image.txt', $response[0]['basename']);
    }

    public function testGarbageOptionIsAppliedToFile()
    {
        $context = $this->handle($this->createInstance($this->createDependencies()), false);

        $this->assertFalse($context->get('files')[0]->isGarbage());
    }

    public function testInvalidUploadReturnsErrorsAndStopsContext()
    {
        $dependencies = $this->createDependencies();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->validator->method('validate')->willReturn(new ConstraintViolationList([
            new ConstraintViolation('File too large', null, [], null, 'file', null),
        ]));
        $dependencies->resourceManager->expects($this->never())->method('save');

        $context = $this->handle($this->createInstance($dependencies));

        $this->assertTrue($context->isStopped());
        $this->assertNull($context->get('files'));
        $this->assertEquals(400, $context->getResponse()->getStatusCode());
        $this->assertEquals(['success' => false, 'errors' => ['File too large']], json_decode($context->getResponse()->getContent(), true));
    }
}

class FileUploadEndpointTypeDependencies
{
    public FileFactory $fileFactory;
    public ResourceManager|MockObject $resourceManager;
    public ValidatorInterface|MockObject $validator;
}