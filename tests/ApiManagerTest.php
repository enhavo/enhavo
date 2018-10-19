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
        self::$apiManager = new ApiManager(
            new HttpAdapter(
                [
                    'credentials' => [
                        'client_id' => getenv('CLIENT_ID'),
                        'client_secret' => getenv('CLIENT_SECRET'),
                    ],
                ],
                (new Logger('debug'))->pushHandler(
                    new StreamHandler(__DIR__.'/../var/log/api.log')
                )
            )
        );

        self::$groupId = getenv('GROUP_ID');
        self::$formId = getenv('FORM_ID');
    }

    public function testGetAccessToken()
    {
        $this->assertArrayHasKey('access_token', self::$apiManager->getAccessToken());
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
    }
}
