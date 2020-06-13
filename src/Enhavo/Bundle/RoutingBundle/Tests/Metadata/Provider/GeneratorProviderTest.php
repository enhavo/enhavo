<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-13
 * Time: 10:48
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\RoutingBundle\Metadata\Provider\GeneratorProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class GeneratorProviderTest extends TestCase
{
    public function testProvide()
    {
        $provider = new GeneratorProvider();
        $metadata = new Metadata('SomeClass');
        $provider->provide($metadata, [
            'generators' => [
                'prefix' => [
                    'type' => 'prefix_type',
                    'option1' => 'value1',
                    'option2' => 'value2'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getGenerators());
        $this->assertEquals('prefix_type', $metadata->getGenerators()[0]->getType());
        $this->assertEquals([
            'option1' => 'value1',
            'option2' => 'value2'
        ], $metadata->getGenerators()[0]->getOptions());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $provider = new GeneratorProvider();
        $metadata = new \Enhavo\Component\Metadata\Metadata('SomeClass');
        $provider->provide($metadata, []);
    }
}
