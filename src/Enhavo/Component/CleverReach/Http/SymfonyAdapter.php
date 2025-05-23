<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\CleverReach\Http;

use Enhavo\Component\CleverReach\Exception\AuthorizeException;
use Enhavo\Component\CleverReach\Exception\RequestException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyAdapter implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const API_ENDPOINT = 'https://rest.cleverreach.com';
    public const API_ENDPOINT_CONFIG_KEY = 'api_endpoint';

    /** @var string */
    private $accessToken;

    private string $endpoint;

    /**
     * Client constructor.
     */
    public function __construct(private array $config = [], ?LoggerInterface $logger = null, private ?HttpClientInterface $client = null)
    {
        $endpoint = $config[self::API_ENDPOINT_CONFIG_KEY] ?? self::API_ENDPOINT;

        $this->endpoint = $endpoint;

        if ($logger) {
            $this->logger = $logger;
        }

        if (null == $this->client) {
            $this->client = HttpClient::create();
        }
    }

    public function authorize(string $clientId, string $clientSecret)
    {
        try {
            $response = $this->client->request('POST', '/oauth/token.php', [
                'base_uri' => $this->endpoint,
                'auth_basic' => [
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
            $data = json_decode($response->getContent(), true);

            if (!isset($data['access_token'])) {
                throw new AuthorizeException('Access token was not provided');
            }

            $this->accessToken = $data['access_token'];

            return true;
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            $this->log(LogLevel::ERROR, $e->getMessage());
            throw new AuthorizeException($e->getMessage());
        }
    }

    public function action(string $method, string $path, array $data = [])
    {
        $this->log(LogLevel::INFO, "Request via \"{$method}\" on \"{$path}\"");

        if (!empty($data)) {
            $this->log(LogLevel::INFO, 'Request data.', ['request' => $data]);
        }

        try {
            $response = $this->client->request(strtoupper($method), $path, ['base_uri' => $this->endpoint, 'headers' => ['Authorization' => "Bearer {$this->getAccessToken()}", 'Accept' => 'application/json'], 'json' => $data]);
            $data = json_decode($response->getContent(), true);
            $this->log(LogLevel::INFO, 'Response data.', ['response' => $data]);
        } catch (TransportExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            $this->log(LogLevel::ERROR, $e->getMessage());
            throw new RequestException($e->getMessage());
        } catch (ClientExceptionInterface $e) {
            if (404 !== $e->getCode()) {
                $this->log(LogLevel::ERROR, $e->getMessage());
                throw new RequestException($e->getMessage());
            }
            $data = json_decode($e->getResponse()->getContent(false), true);
            $this->log(LogLevel::INFO, 'Response data.', ['response' => $data]);
        }

        return $data;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Log message.
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
