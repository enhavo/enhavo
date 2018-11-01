<?php

namespace rdoepner\CleverReach\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

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

            if ($this->logger) {
                $this->logger->error(
                    'Response data.',
                    [
                        'body' => $data,
                    ]
                );
            }
        }

        if (isset($data['access_token'])) {
            $this->config['access_token'] = $data['access_token'];
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
    public function action(string $method, string $path, array $data = [])
    {
        if ($this->logger) {
            $this->logger->info("Request via \"{$method}\" on \"{$path}\"");
        }

        if (!$accessToken = $this->getAccessToken()) {
            throw new \InvalidArgumentException('The access token is missing.');
        }

        $options = [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
            ],
            'json' => $data,
        ];

        if (!empty($data) && $this->logger) {
            $this->logger->info(
                'Request data.',
                [
                    'body' => $data,
                ]
            );
        }

        try {
            $response = $this->request($method, $path, $options);

            $data = json_decode(
                $response->getBody()->getContents(),
                true
            );

            if ($this->logger) {
                $this->logger->info(
                    'Response data.',
                    [
                        'body' => $data,
                    ]
                );
            }
        } catch (ClientException $e) {
            $data = json_decode(
                $e->getResponse()->getBody()->getContents(),
                true
            );

            if ($this->logger) {
                $this->logger->error(
                    'Response data.',
                    [
                        'body' => $data,
                    ]
                );
            }
        }

        return $data;
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
     * @param array $config
     */
    private function configure(array $config)
    {
        $this->config = [
            'access_token' => $config['access_token'] ?? null,
        ];
    }
}
