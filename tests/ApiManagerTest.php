<?php

namespace rdoepner\CleverReach\Tests\Http;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use rdoepner\CleverReach\ApiManager;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

class ApiManagerTest extends TestCase
{
    /**
     * @var ApiManager
     */
    protected static $apiManager;

    /**
     * @var string
     */
    protected static $groupId;

    public static function setUpBeforeClass()
    {
        $httpAdapter = new HttpAdapter(
            [
                'access_token' => getenv('GROUP_ID'),
            ],
            (new Logger('debug'))->pushHandler(
                new StreamHandler(dirname(__DIR__) . '/var/log/api.log')
            )
        );

        $httpAdapter->authorize(
            getenv('CLIENT_ID'),
            getenv('CLIENT_SECRET')
        );

        self::$apiManager = new ApiManager($httpAdapter);
        self::$groupId = getenv('GROUP_ID');
    }

    public function testCreateSubscriber()
    {
        $response = self::$apiManager->createSubscriber(
            'john.doe@example.org',
            self::$groupId,
            false,
            [
                'salutation' => 'Mr.',
                'firstname' => 'John',
                'lastname' => 'Doe',
            ]
        );

        $this->assertArrayHasKey('email', $response);
        $this->assertEquals('john.doe@example.org', $response['email']);
    }

    public function testGetSubscriber()
    {
        $response = self::$apiManager->getSubscriber(
            'john.doe@example.org',
            self::$groupId
        );

        $this->assertArrayHasKey('email', $response);
        $this->assertEquals('john.doe@example.org', $response['email']);

        $response = self::$apiManager->getSubscriber(
            'jane.doe@example.org',
            self::$groupId
        );

        $this->assertArrayHasKey('error', $response);
        $this->assertArraySubset(
            $response,
            [
                'error' => [
                    'code' => 404,
                    'message' => 'Not Found: invalid receiver',
                ],
            ]
        );
    }

    public function testSetSubscriberStatus()
    {
        $response = self::$apiManager->setSubscriberStatus(
            'john.doe@example.org',
            self::$groupId,
            true
        );

        $this->assertTrue($response);

        $response = self::$apiManager->getSubscriber(
            'john.doe@example.org',
            self::$groupId
        );

        $this->assertArrayHasKey('active', $response);
        $this->assertTrue($response['active']);

        $response = self::$apiManager->setSubscriberStatus(
            'john.doe@example.org',
            self::$groupId,
            false
        );

        $this->assertTrue($response);

        $response = self::$apiManager->getSubscriber(
            'john.doe@example.org',
            self::$groupId
        );

        $this->assertArrayHasKey('active', $response);
        $this->assertFalse($response['active']);
    }

    public function testDeleteSubscriber()
    {
        $response = self::$apiManager->deleteSubscriber(
            'john.doe@example.org',
            self::$groupId
        );

        $this->assertTrue($response);

        $response = self::$apiManager->deleteSubscriber(
            'jane.doe@example.org',
            self::$groupId
        );

        $this->assertArrayHasKey('error', $response);
        $this->assertArraySubset(
            $response,
            [
                'error' => [
                    'code' => 404,
                    'message' => 'Not Found: invalid receiver',
                ],
            ]
        );
    }
}
