<?php

namespace rdoepner\CleverReach\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\MessageInterface;
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
    public function authorize()
    {
        if (!isset($this->config['credentials']['client_id'], $this->config['credentials']['client_secret'])) {
            throw new \InvalidArgumentException('The credentials are missing.');
        }

        try {
            $response = $this->request(
                'post',
                '/oauth/token.php',
                [
                    'auth' => [
                        $this->config['credentials']['client_id'],
                        $this->config['credentials']['client_secret'],
                    ],
                    'json' => [
                        'grant_type' => 'client_credentials',
                    ],
                ]
            );
            $payload = $response->getBody()->getContents();
        } catch (ClientException $e) {
            $payload = $e->getResponse()->getBody()->getContents();
            $this->log($payload, LogLevel::ERROR);
        }

        if ($decoded = json_decode($payload, true)) {
            if (isset($decoded['access_token'])) {
                $this->config['access_token'] = $decoded['access_token'];
            }
        }

        return $decoded;
    }

    /**
     * {@inheritdoc}
     */
    public function action(string $method, string $path, array $data = [])
    {
        $this->log("Request via \"{$method}\" on \"{$path}\"", LogLevel::DEBUG);

        if (!isset($this->config['access_token'])) {
            throw new \InvalidArgumentException('The access token is missing.');
        }

        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->config['access_token']}",
            ],
            'json' => $data,
        ];

        if ($this->logger && !empty($data)) {
            $tapMiddleware = Middleware::tap(
                function (MessageInterface $request) {
                    $this->log($request->getBody()->getContents(), LogLevel::DEBUG);
                }
            );
            $options = array_merge(
                $options,
                [
                    'handler' => $tapMiddleware(
                        $this->getConfig('handler')
                    ),
                ]
            );
        }

        try {
            $response = $this->request($method, $path, $options);
            $payload = $response->getBody()->getContents();
            $this->log($payload, LogLevel::INFO);
        } catch (ClientException $e) {
            $payload = $e->getResponse()->getBody()->getContents();
            $this->log($payload, LogLevel::ERROR);
        }

        return json_decode($payload, true);
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
     * @param string $message
     * @param string $type
     */
    protected function log(string $message, string $type = LogLevel::INFO)
    {
        if ($this->logger) {
            if (method_exists($this->logger, $type)) {
                $this->logger->$type($message);
            }
        }
    }

    /**
     * @param array $config
     */
    private function configure(array $config)
    {
        $this->config = [
            'credentials' => [
                'client_id' => $config['credentials']['client_id'] ?? null,
                'client_secret' => $config['credentials']['client_secret'] ?? null,
            ],
            'access_token' => $config['access_token'] ?? null,
        ];
    }
}
