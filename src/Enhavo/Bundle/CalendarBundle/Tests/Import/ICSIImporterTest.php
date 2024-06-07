<?php

namespace Enhavo\Bundle\CalendarBundle\Tests\Import;

use Enhavo\Bundle\CalendarBundle\Import\ICSImporter;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\FileNotFound\RemoteFileNotFoundHandler;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Enhavo\Component\CleverReach\Http\SymfonyAdapter;
use Enhavo\Component\CleverReach\Tests\Http\SymfonyAdapterTest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class ICSIImporterTest extends TestCase
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
        $dependencies = new ICSIImporterTestDependencies();
        $dependencies->importerName = "Test";
        $dependencies->config = ['url' => 'http://127.0.0.1:1234/importer',];
        $dependencies->client = new CurlHttpClient();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new ICSImporter(
            $dependencies->importerName,
            $dependencies->config,
            $dependencies->client,
        );

        return $instance;
    }

    public function testImport()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $appointments = $instance->import('http://127.0.0.1:1234/create/from/uri');

        $this->assertEquals(1, count($appointments));
    }
}

class ICSIImporterTestDependencies
{
    /** @var string */
    public $importerName;

    /** @var array */
    public $config;

    /** @var ClientInterface|MockObject */
    public $client;
}
