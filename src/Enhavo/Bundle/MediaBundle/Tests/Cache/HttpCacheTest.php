<?php

namespace Enhavo\Bundle\MediaBundle\Tests\Cache;

use Enhavo\Bundle\MediaBundle\Cache\HttpCache;
use Enhavo\Bundle\MediaBundle\Content\UrlContent;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class HttpCacheTest extends TestCase
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
        $dependencies = new HttpCacheTestDependecies();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->client = new CurlHttpClient();
        $dependencies->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new HttpCache(
            $dependencies->requestStack,
            $dependencies->client,
            null,
            null,
            null,
            2,
            'GET'
        );

        $instance->setUrlGenerator($dependencies->urlGenerator);

        return $instance;
    }

    public function testGetContent()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
        $dependencies->urlGenerator->method('generateFormat')->willReturn("/http-cache-test");
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('getHost')->willReturn("127.0.0.1");
        $request->method('getPort')->willReturn("1234");
        $request->method('getScheme')->willReturn("http");

        $dependencies->requestStack->method('getMainRequest')->willReturn($request);

        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $instance->invalid($file, "format");

        $this->expectNotToPerformAssertions();
    }
}

class HttpCacheTestDependecies
{
    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var ClientInterface|MockObject */
    public $client;

    /** @var UrlGeneratorInterface|MockObject */
    public $urlGenerator;
}
