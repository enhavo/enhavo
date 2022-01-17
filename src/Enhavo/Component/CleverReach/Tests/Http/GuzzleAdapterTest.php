<?php

namespace Enhavo\Component\CleverReach\Tests\Http;

use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Enhavo\Component\CleverReach\Http\GuzzleAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class GuzzleAdapterTest extends TestCase
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

    private function createGuzzleAdapter()
    {
        return new GuzzleAdapter([
            'api_endpoint' => 'http://127.0.0.1:1234',
        ]);
    }

    public function testAuthorize()
    {
        $guzzle = $this->createGuzzleAdapter();
        $result = $guzzle->authorize('cli3ntId', 'clientS3cr3t');
        $this->assertTrue($result);
        $this->assertEquals('s3cr3t_acc3ss_token', $guzzle->getAccessToken());
    }

    public function testFailAuthorize()
    {
        $this->expectException(AuthorizeException::class);
        $guzzle = $this->createGuzzleAdapter();
        $guzzle->authorize('something', 'wrong');
    }

    public function testAction()
    {
        $guzzle = $this->createGuzzleAdapter();
        $guzzle->authorize('cli3ntId', 'clientS3cr3t');
        $data = $guzzle->action('GET', '/action/test');
        $this->assertEquals('test', $data['test']);
    }

    public function testFailAction()
    {
        $this->expectException(RequestException::class);
        $guzzle = $this->createGuzzleAdapter();
        $guzzle->authorize('cli3ntId', 'clientS3cr3t');
        $guzzle->action('GET', '/server/error');
    }
}
