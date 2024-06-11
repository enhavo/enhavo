<?php

namespace Enhavo\Bundle\MediaBundle\Tests\Factory;

use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

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
        $dependencies->provider = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $dependencies->client = new CurlHttpClient();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new FileFactory(
            $dependencies->className,
            $dependencies->provider,
            $dependencies->client,
        );

        return $instance;
    }

    public function testCreateFromUri()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri');

        $this->assertEquals("test.txt", $file->getFilename());
    }

    public function testCreateFromUriWithFileName()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri', "filename.txt");

        $this->assertEquals("filename.txt", $file->getFilename());
    }

    public function testCreateFromUriWithMimeType()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $file = $instance->createFromUri('http://127.0.0.1:1234/create/from/uri', "filename.txt");

        $this->assertEquals("text/plain", $file->getMimeType());
    }
}

class FileFactoryTestDependencies
{
    /** @var string */
    public $className;

    /** @var ProviderInterface|MockObject */
    public $provider;

    /** @var ClientInterface|MockObject */
    public $client;
}
