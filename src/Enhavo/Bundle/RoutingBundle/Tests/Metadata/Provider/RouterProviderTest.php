<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-13
 * Time: 10:48
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\RoutingBundle\Metadata\Provider\RouterProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class RouterProviderTest extends TestCase
{
    public function testProvide()
    {
        $provider = new RouterProvider();
        $metadata = new Metadata('SomeClass');
        $provider->provide($metadata, [
            'router' => [
                'admin' => [
                    'type' => 'route',
                    'option1' => 'value1',
                    'option2' => 'value2'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getRouter());
        $this->assertEquals('route', $metadata->getRouter()[0]->getType());
        $this->assertEquals('admin', $metadata->getRouter()[0]->getName());
        $this->assertEquals([
            'option1' => 'value1',
            'option2' => 'value2'
        ], $metadata->getRouter()[0]->getOptions());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $provider = new RouterProvider();
        $metadata = new \Enhavo\Component\Metadata\Metadata('SomeClass');
        $provider->provide($metadata, []);
    }
}
