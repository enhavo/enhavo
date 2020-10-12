<?php

namespace Enhavo\Component\CleverReach\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Guzzle extends Client implements AdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const API_ENDPOINT = 'https://rest.cleverreach.com';

    const ADAPTER_CONFIG_KEY = 'adapter_config';

    /**
     * @var array
     */
    private $config;

    /**
     * Client constructor.
     *
     * @param array                $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        $adapterConfig = [
            'base_uri' => self::API_ENDPOINT,
        ];

        if (isset($config[self::ADAPTER_CONFIG_KEY]) && is_array($config[self::ADAPTER_CONFIG_KEY])) {
            $adapterConfig = array_merge($adapterConfig, $config[self::ADAPTER_CONFIG_KEY]);
        }

        parent::__construct($adapterConfig);

        $this->configure($config);

        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(string $clientId, string $clientSecret)
    {
        try {
            $response = $this->request(
                'post',
                '/oauth/token.php',
                [
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
                ]
            );
            $data = json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (ClientException $e) {
            $data = json_decode(
                $e->getResponse()->getBody()->getContents(),
                true
            );
            $this->log(
                'Response data.',
                [
                    'response' => $data,
                ],
                LogLevel::ERROR
            );
        }

        if (isset($data['access_token'])) {
            $this->config['access_token'] = $data['access_token'];
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function action(string $method, string $path, array $data = [])
    {
        $this->log("Request via \"{$method}\" on \"{$path}\"");

        if (!empty($data)) {
            $this->log('Request data.', ['request' => $data]);
        }

        try {
            $response = $this->request(
                $method,
                $path,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$this->getAccessToken()}",
                        'Accept' => 'application/json',
                    ],
                    'json' => $data,
                ]
            );
            $data = json_decode(
                $response->getBody()->getContents(),
                true
            );
            $this->log('Response data.', ['response' => $data]);
        } catch (ClientException $e) {
            $data = json_decode(
                $e->getResponse()->getBody()->getContents(),
                true
            );
            $this->log(
                'Response data.',
                [
                    'response' => $data,
                ],
                LogLevel::ERROR
            );
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->getConfig('access_token');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($key = null)
    {
        if (null !== $key) {
            if ($key == self::ADAPTER_CONFIG_KEY) {
                return parent::getConfig();
            }

            return $this->config[$key] ?? parent::getConfig($key);
        }

        return array_merge(
            $this->config,
            [
                self::ADAPTER_CONFIG_KEY => parent::getConfig(),
            ]
        );
    }

    /**
     * Log message.
     *
     * @param string $message
     * @param array  $data
     * @param string $type
     */
    protected function log(string $message, array $data = [], $type = LogLevel::INFO)
    {
        if ($this->logger) {
            $this->logger->$type($message, $data);
        }
    }

    /**
     * Configure defaults.
     *
     * @param array $config
     */
    private function configure(array $config)
    {
        $this->config = [
            'access_token' => $config['access_token'] ?? null,
        ];
    }
}
