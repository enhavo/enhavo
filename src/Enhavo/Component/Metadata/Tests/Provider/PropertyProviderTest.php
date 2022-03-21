<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 21:43
 */

namespace Enhavo\Component\Metadata\Tests\Provider;

use Enhavo\Component\Metadata\Exception\ProviderException;
use Enhavo\Component\Metadata\Provider\PropertyProvider;
use Enhavo\Component\Metadata\Extension\PropertyInterface;
use Enhavo\Component\Metadata\Extension\PropertyTrait;
use Enhavo\Component\Metadata\Metadata;
use PHPUnit\Framework\TestCase;

class PropertyProviderTest extends TestCase
{
    public function testProvide()
    {
        $metadata = new PropertyProviderMetadata('SomeClass');
        $provider = new PropertyProvider();
        $provider->provide($metadata, [
            'properties' => [
                'name' => [
                    'type' => 'property',
                    'options' => 'value'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getProperties());
        $this->assertEquals([
            'type' => 'property',
            'options' => 'value'
        ], $metadata->getProperty('name')->getOptions());
    }

    public function testInvalidInterface()
    {
        $this->expectException(ProviderException::class);

        $metadata = new PropertyProviderInvalidMetadata('SomeClass');
        $provider = new PropertyProvider();
        $provider->provide($metadata, []);
    }
}

class PropertyProviderMetadata extends Metadata implements PropertyInterface
{
    use PropertyTrait;
}

class PropertyProviderInvalidMetadata extends Metadata
{

}
