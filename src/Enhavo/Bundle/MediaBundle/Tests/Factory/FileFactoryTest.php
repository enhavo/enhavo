<?php

namespace Enhavo\Bundle\MediaBundle\Tests\Factory;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileFactoryTest extends TestCase
{
    /** @var WebServerManager */
    private static $server;

    public static function setUpBeforeClass(): void
    {
        if (self::$server === null) {
            self::$server = new WebServerManager(__DIR__.'/../fixtures/server', '127.0.0.1', 1234, 'index.php', '/ready');
            self::$server->start();
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server instanceof WebServerManager) {
            self::$server->quit();
            self::$server = null;
        }
    }

    private function createDependencies()
    {
        $dependencies = new FileFactoryTestDependencies();
        $dependencies->className = File::class;
        $dependencies->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $dependencies->checksumGenerator = $this->getMockBuilder(ChecksumGeneratorInterface::class)->getMock();
        $dependencies->client = new CurlHttpClient();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new FileFactory(
            $dependencies->className,
            $dependencies->tokenGenerator,
            $dependencies->checksumGenerator,
            $dependencies->client,
        );

        return $instance;
    }

    public function testCreateFromUri()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenGenerator->method('generateToken')->willReturn('1234');

        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri');

        $this->assertEquals("test.txt", $file->getBasename());
        $this->assertEquals("1234", $file->getToken());
    }

    public function testCreateFromUriWithFileName()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenGenerator->method('generateToken')->willReturn('1234');

        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri', "filename.txt");

        $this->assertEquals("filename.txt", $file->getBasename());
        $this->assertEquals("1234", $file->getToken());
    }

    public function testCreateFromUriWithMimeType()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenGenerator->method('generateToken')->willReturn('1234');

        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri', "filename.txt");

        $this->assertEquals("text/plain", $file->getMimeType());
        $this->assertEquals("1234", $file->getToken());
    }
}

class FileFactoryTestDependencies
{
    public string $className;
    public HttpClientInterface|MockObject $client;
    public TokenGeneratorInterface|MockObject $tokenGenerator;
    public ChecksumGeneratorInterface|MockObject $checksumGenerator;
}
