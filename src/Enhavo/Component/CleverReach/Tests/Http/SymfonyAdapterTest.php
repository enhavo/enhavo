<?php

namespace Enhavo\Component\CleverReach\Tests\Http;

use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Enhavo\Component\CleverReach\Http\SymfonyAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class SymfonyAdapterTest extends TestCase
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

    private function createSymfonyAdapter()
    {
        return new SymfonyAdapter(['api_endpoint' => 'http://127.0.0.1:1234'], null, new CurlHttpClient());
    }

    public function testAuthorize()
    {
        $symfony = $this->createSymfonyAdapter();
        $result = $symfony->authorize('cli3ntId', 'clientS3cr3t');
        $this->assertTrue($result);
        $this->assertEquals('s3cr3t_acc3ss_token', $symfony->getAccessToken());
    }

    public function testFailAuthorize()
    {
        $this->expectException(AuthorizeException::class);
        $symfony = $this->createSymfonyAdapter();
        $symfony->authorize('something', 'wrong');
    }

    public function testAction()
    {
        $symfony = $this->createSymfonyAdapter();
        $symfony->authorize('cli3ntId', 'clientS3cr3t');
        $data = $symfony->action('get', '/action/test');
        $this->assertEquals('test', $data['test']);
    }

    public function testFailAction()
    {
        $this->expectException(RequestException::class);
        $symfony = $this->createSymfonyAdapter();
        $symfony->authorize('cli3ntId', 'clientS3cr3t');
        $symfony->action('GET', '/server/error');
    }
}
