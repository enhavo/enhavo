<?php

namespace Enhavo\Bundle\MediaBundle\Tests\FileNotFound;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\FileNotFound\RemoteFileNotFoundHandler;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemoteFileNotFoundHandlerTest extends TestCase
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
        $dependencies = new RemoteFileNotFoundHandlerTestDependecies();
        $dependencies->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $dependencies->storage = $this->getMockBuilder(StorageInterface::class)->getMock();
        $dependencies->client = new CurlHttpClient();
        $dependencies->exception = new FileNotFoundException();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new RemoteFileNotFoundHandler(
            $dependencies->urlGenerator,
            $dependencies->client,
        );

        return $instance;
    }

    public function testHandleFileNotFound()
    {
        $dependencies = $this->createDependencies();
        $dependencies->urlGenerator->method('generate')->willReturn('/file-not-found');
        $instance = $this->createInstance($dependencies);
        $file = new File();

        $instance->setParameters([
            RemoteFileNotFoundHandler::SERVER_URL_PARAMETER => 'http://127.0.0.1:1234'
        ]);
        $instance->handleLoad($file, $dependencies->storage, $dependencies->exception);

        $this->assertEquals("File could not be found.", $file->getContent()->getContent());
    }
}

class RemoteFileNotFoundHandlerTestDependecies
{
    public UrlGeneratorInterface|MockObject $urlGenerator;
    public HttpClientInterface|MockObject $client;
    public StorageInterface|MockObject $storage;
    public FileNotFoundException $exception;
}
