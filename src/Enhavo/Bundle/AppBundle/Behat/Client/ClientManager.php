<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Client;

use Symfony\Component\Panther\Client as PantherClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class ClientManager
{
    /**
     * @var string|null
     */
    protected $webServerDir;

    /**
     * @var WebServerManager|null
     */
    protected $webServerManager;

    /**
     * @var string|null
     */
    protected $baseUri;

    /**
     * @var PantherClient[] All Panther clients, the first one is the primary one (aka self::$pantherClient)
     */
    protected $pantherClients = [];

    /**
     * @var array
     */
    protected $defaultOptions = [
        'webServerDir' => __DIR__.'/../../../../../../public', // the Flex directory structure
        'hostname' => '127.0.0.1',
        'port' => 9080,
        'router' => '',
        'external_base_uri' => null,
        'readinessPath' => '',
    ];

    public function stopWebServer()
    {
        if (null !== $this->webServerManager) {
            $this->webServerManager->quit();
            $this->webServerManager = null;
        }

        foreach ($this->pantherClients as $i => $pantherClient) {
            $pantherClient->quit(true);
        }

        $this->pantherClients = [];
        $this->baseUri = null;
    }

    /**
     * @param array $options see {@see $defaultOptions}
     */
    public function startWebServer(array $options = []): void
    {
        if (null !== $this->webServerManager) {
            return;
        }

        if ($externalBaseUri = $options['external_base_uri'] ?? $_SERVER['PANTHER_EXTERNAL_BASE_URI'] ?? $this->defaultOptions['external_base_uri']) {
            $this->baseUri = $externalBaseUri;

            return;
        }

        $options = [
            'webServerDir' => self::getWebServerDir($options),
            'hostname' => $options['hostname'] ?? $this->defaultOptions['hostname'],
            'port' => (int) ($options['port'] ?? $_SERVER['PANTHER_WEB_SERVER_PORT'] ?? $this->defaultOptions['port']),
            'router' => $options['router'] ?? $_SERVER['PANTHER_WEB_SERVER_ROUTER'] ?? $this->defaultOptions['router'],
            'readinessPath' => $options['readinessPath'] ?? $_SERVER['PANTHER_READINESS_PATH'] ?? $this->defaultOptions['readinessPath'],
        ];

        $this->webServerManager = new WebServerManager(...array_values($options));
        $this->webServerManager->start();

        $this->baseUri = sprintf('http://%s:%s', $options['hostname'], $options['port']);
    }

    public function isWebServerStarted()
    {
        return $this->webServerManager && $this->webServerManager->isStarted();
    }

    /**
     * Creates the primary browser.
     */
    public function createClient(array $webServerOptions = []): PantherClient
    {
        $this->startWebServer($webServerOptions);
        $client = PantherClient::createChromeClient(null, null, [], $this->baseUri);
        $this->pantherClients[] = $client;

        return $client;
    }

    private function getWebServerDir(array $options)
    {
        if (isset($options['webServerDir'])) {
            return $options['webServerDir'];
        }

        if (null !== $this->webServerDir) {
            return $this->webServerDir;
        }

        if (!isset($_SERVER['PANTHER_WEB_SERVER_DIR'])) {
            return $this->defaultOptions['webServerDir'];
        }

        if (0 === strpos($_SERVER['PANTHER_WEB_SERVER_DIR'], './')) {
            return getcwd().substr($_SERVER['PANTHER_WEB_SERVER_DIR'], 1);
        }

        return $_SERVER['PANTHER_WEB_SERVER_DIR'];
    }

    public function getClient()
    {
        if (0 === count($this->pantherClients)) {
            $this->createClient();
        }

        return $this->pantherClients[0];
    }

    public function getClients(): array
    {
        return $this->pantherClients;
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }
}
