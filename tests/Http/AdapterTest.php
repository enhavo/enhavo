<?php

use PHPUnit\Framework\TestCase;
use rdoepner\CleverReach\Http\Guzzle as HttpAdapter;

class AdapterTest extends TestCase
{
    public function testConfig()
    {
        $adapter = new HttpAdapter(
            [
                'access_token' => 'ACCESS_TOKEN',
            ]
        );

        $this->assertArrayHasKey('access_token', $adapter->getConfig());
        $this->assertEquals('ACCESS_TOKEN', $adapter->getConfig('access_token'));

        $this->assertArrayHasKey('adapter_config', $adapter->getConfig());
        $this->assertArrayHasKey('base_uri', $adapter->getConfig('adapter_config'));
        $this->assertEquals('https://rest.cleverreach.com', $adapter->getConfig('base_uri'));

        $adapter = new HttpAdapter(
            [
                'adapter_config' => [
                    'base_uri' => 'https://rest.example.org',
                ],
            ]
        );

        $this->assertArrayHasKey('adapter_config', $adapter->getConfig());
        $this->assertArrayHasKey('base_uri', $adapter->getConfig('adapter_config'));
        $this->assertEquals('https://rest.example.org', $adapter->getConfig('base_uri'));
    }
}
