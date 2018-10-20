<?php

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
     * @var int
     */
    protected static $groupId;

    /**
     * @var int
     */
    protected static $formId;

    public static function setUpBeforeClass()
    {
        self::$groupId = getenv('GROUP_ID');
        self::$formId = getenv('FORM_ID');

        $adapter = new HttpAdapter(
            [
                'access_token' => 'INVALID_TOKEN',
            ],
            (new Logger('debug'))->pushHandler(
                new StreamHandler(__DIR__.'/../var/log/api.log')
            )
        );

        $response = $adapter->authorize(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        if (!isset($response['access_token'])) {
            throw new \RuntimeException('Authorization failed.');
        }

        self::$apiManager = new ApiManager(
            new HttpAdapter(
                [
                    'access_token' => $response['access_token'],
                ],
                (new Logger('debug'))->pushHandler(
                    new StreamHandler(__DIR__.'/../var/log/api.log')
                )
            )
        );
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
            'jane.doe@example.org@example.org',
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
