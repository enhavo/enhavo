<?php

namespace Enhavo\Component\CleverReach\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class GuzzleAdapter implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const API_ENDPOINT = 'https://rest.cleverreach.com';
    const API_ENDPOINT_CONFIG_KEY = 'api_endpoint';

    /** @var array */
    private $config;

    /** @var Client */
    private $client;

    /** @var string */
    private $accessToken;

    /**
     * Client constructor.
     *
     * @param array $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $endpoint = isset($config[self::API_ENDPOINT_CONFIG_KEY]) ? $config[self::API_ENDPOINT_CONFIG_KEY] : self::API_ENDPOINT;

        $this->client = new Client([
            'base_uri' => $endpoint
        ]);

        if ($logger) {
            $this->logger = $logger;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(string $clientId, string $clientSecret)
    {
        try {
            $response = $this->client->request('post', '/oauth/token.php', [
                'auth' => [
                    $clientId,
                    $clientSecret,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['access_token'])) {
                throw new AuthorizeException('Access token was not provided');
            }

            $this->accessToken = $data['access_token'];

            return true;
        } catch (ClientException $e) {
            $this->log(LogLevel::ERROR, $e->getMessage());
            throw new AuthorizeException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function action(string $method, string $path, array $data = [])
    {
        $this->log(LogLevel::INFO,"Request via \"{$method}\" on \"{$path}\"");

        if (!empty($data)) {
            $this->log(LogLevel::INFO, 'Request data.', ['request' => $data]);
        }

        try {
            $response = $this->client->request($method, $path, ['headers' => ['Authorization' => "Bearer {$this->getAccessToken()}", 'Accept' => 'application/json',], 'json' => $data,]);
            $data = json_decode($response->getBody()->getContents(), true);
            $this->log(LogLevel::INFO, 'Response data.', ['response' => $data]);


        } catch (ServerException $e) {
            $this->log(LogLevel::ERROR, $e->getMessage());
            throw new RequestException($e->getMessage());
        } catch (ClientException $e) {
            if ($e->getCode() !== 404) {
                $this->log(LogLevel::ERROR, $e->getMessage());
                throw new RequestException($e->getMessage());
            }
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $this->log(LogLevel::INFO, 'Response data.', ['response' => $data]);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Log message.
     *
     * @param string $level
     * @param string $message
     * @param array  $data
     */
    private function log(string $level, string $message, array $data = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $data);
        }
    }

    public function getConfig($key = null)
    {
        // TODO: Implement getConfig() method.
    }
}
