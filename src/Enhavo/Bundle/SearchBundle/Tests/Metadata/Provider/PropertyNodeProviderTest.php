<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 21:51
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\Provider\IndexProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class PropertyNodeProviderTest extends TestCase
{
    public function testProvide()
    {
        $provider = new IndexProvider();
        $metadata = new Metadata('SomeClass');
        $provider->provide($metadata, [
            'properties' => [
                'name' => [
                    'type' => 'text',
                    'option1' => 'value1',
                    'option2' => 'value2'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getProperties());
        $this->assertEquals('name', $metadata->getProperties()[0]->getProperty());
        $this->assertEquals('text', $metadata->getProperties()[0]->getType());
        $this->assertEquals([
            'option1' => 'value1',
            'option2' => 'value2'
        ], $metadata->getProperties()[0]->getOptions());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $provider = new IndexProvider();
        $metadata = new \Enhavo\Component\Metadata\Metadata('SomeClass');
        $provider->provide($metadata, []);
    }
}
