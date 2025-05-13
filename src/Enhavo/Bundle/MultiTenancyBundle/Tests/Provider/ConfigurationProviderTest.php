<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Provider;

use Enhavo\Bundle\MultiTenancyBundle\Provider\ConfigurationProvider;
use PHPUnit\Framework\TestCase;

class ConfigurationProviderTest extends TestCase
{
    public function testProvider()
    {
        $provider = new ConfigurationProvider([
            'test_key' => [
                'base_url' => 'http://localhost.com',
                'domains' => [
                    'localhost.com', 'something.com',
                ],
                'name' => 'Test',
                'role' => 'ROLE_TENANT_TEST',
            ],
            'other_key' => [
                'base_url' => 'http://other.com',
                'domains' => [
                    'other.com',
                ],
            ],
        ]);

        $tenants = $provider->getTenants();

        $this->assertCount(2, $tenants);
        $this->assertEquals('test_key', $tenants[0]->getKey());
        $this->assertEquals('http://localhost.com', $tenants[0]->getBaseUrl());
        $this->assertEquals(['localhost.com', 'something.com'], $tenants[0]->getDomains());
        $this->assertEquals('ROLE_TENANT_TEST', $tenants[0]->getRole());
        $this->assertEquals('Test', $tenants[0]->getName());
    }
}
