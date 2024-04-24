<?php

namespace Enhavo\Bundle\MediaBundle\Tests\FileNotFound;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\FileNotFound\RemoteFileNotFoundHandler;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Enhavo\Component\CleverReach\Http\GuzzleAdapter;
use Enhavo\Component\CleverReach\Tests\Http\GuzzleAdapterTest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

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
        $dependencies->client = new CurlHttpClient();

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
        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $content = $this->getMockBuilder(ContentInterface::class)->getMock();
        $tmpFile = tmpfile();
        $meta_data = stream_get_meta_data($tmpFile);
        $content->method('getFilePath')->willReturn($meta_data['uri']);

        $file->method('getContent')->willReturn($content);

        $instance->handleFileNotFound($file, [RemoteFileNotFoundHandler::SERVER_URL_PARAMETER => 'http://127.0.0.1:1234']);

        $this->assertEquals("File could not be found.", file_get_contents($meta_data['uri']));
    }
}

class RemoteFileNotFoundHandlerTestDependecies
{
    /** @var UrlGeneratorInterface|MockObject */
    public $urlGenerator;

    /** @var ClientInterface|MockObject */
    public $client;
}
