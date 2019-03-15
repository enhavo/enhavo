<?php

namespace rdoepner\CleverReach\Tests\Http;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

class AdapterTest extends TestCase
{
    /**
     * @var HttpAdapter
     */
    protected static $httpAdapter;

    public static function setUpBeforeClass()
    {
        self::$httpAdapter = new HttpAdapter(
            [
                'access_token' => 'ACCESS_TOKEN',
            ],
            (new Logger('debug'))->pushHandler(
                new StreamHandler(dirname(dirname(__DIR__)) . '/var/log/api.log')
            )
        );
    }

    public function testConfig()
    {
        $this->assertArrayHasKey('access_token', self::$httpAdapter->getConfig());
        $this->assertEquals('ACCESS_TOKEN', self::$httpAdapter->getConfig('access_token'));
        $this->assertArrayHasKey('base_uri', self::$httpAdapter->getConfig('adapter_config'));
        $this->assertEquals('https://rest.cleverreach.com', self::$httpAdapter->getConfig('base_uri'));
    }

    public function testAuthorize()
    {
        $response = self::$httpAdapter->authorize(
            getenv('CLIENT_ID'),
            getenv('CLIENT_SECRET')
        );

        $this->assertArrayHasKey('access_token', $response);
    }

    public function testGetAccessToken()
    {
        $this->assertNotEquals('ACCESS_TOKEN', self::$httpAdapter->getAccessToken());
    }
}
