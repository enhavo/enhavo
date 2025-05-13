<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Provider\PropertyNodeProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class PropertyNodeProviderTest extends TestCase
{
    public function testProvide()
    {
        $provider = new PropertyNodeProvider();
        $metadata = new Metadata('SomeClass');
        $provider->provide($metadata, [
            'properties' => [
                'first_name' => [
                    'type' => 'text',
                    'option1' => 'value1',
                    'option2' => 'value2',
                ],
            ],
        ]);

        $this->assertCount(1, $metadata->getProperties());
        $this->assertEquals('text', $metadata->getProperty('first_name')->getType());
        $this->assertNull($metadata->getProperty('last_name'));
        $this->assertEquals('firstName', $metadata->getProperties()['firstName']->getProperty());
        $this->assertEquals('text', $metadata->getProperties()['firstName']->getType());
        $this->assertEquals([
            'option1' => 'value1',
            'option2' => 'value2',
        ], $metadata->getProperties()['firstName']->getOptions());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $provider = new PropertyNodeProvider();
        $metadata = new \Enhavo\Component\Metadata\Metadata('SomeClass');
        $provider->provide($metadata, []);
    }
}
