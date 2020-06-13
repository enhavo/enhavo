<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 21:51
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata\Provider;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\Provider\FilterProvider;
use Enhavo\Component\Metadata\Exception\ProviderException;
use PHPUnit\Framework\TestCase;

class FilterProviderTest extends TestCase
{
    public function testProvide()
    {
        $provider = new FilterProvider();
        $metadata = new Metadata('SomeClass');
        $provider->provide($metadata, [
            'filter' => [
                'name' => [
                    'type' => 'text',
                    'data_type' => 'date',
                    'option1' => 'value1',
                    'option2' => 'value2'
                ]
            ]
        ]);

        $this->assertCount(1, $metadata->getFilters());
        $this->assertEquals('name', $metadata->getFilters()[0]->getKey());
        $this->assertEquals('text', $metadata->getFilters()[0]->getType());
        $this->assertEquals('date', $metadata->getFilters()[0]->getDataType());
        $this->assertEquals([
            'option1' => 'value1',
            'option2' => 'value2'
        ], $metadata->getFilters()[0]->getOptions());
    }

    public function testInvalidType()
    {
        $this->expectException(ProviderException::class);
        $provider = new FilterProvider();
        $metadata = new \Enhavo\Component\Metadata\Metadata('SomeClass');
        $provider->provide($metadata, []);
    }
}
