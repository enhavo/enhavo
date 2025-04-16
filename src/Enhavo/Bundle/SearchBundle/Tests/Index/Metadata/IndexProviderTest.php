<?php

namespace Metadata;

use Enhavo\Bundle\SearchBundle\Attribute\Index;
use Enhavo\Bundle\SearchBundle\Index\Metadata\IndexAttributeDriver;
use Enhavo\Bundle\SearchBundle\Index\Metadata\IndexProvider;
use Enhavo\Bundle\SearchBundle\Index\Metadata\Metadata;
use PHPUnit\Framework\TestCase;

class IndexProviderTest extends TestCase
{
    public function testGetEntityName()
    {
        $provider = new IndexProvider();
        $metadata = new Metadata(IndexedEntity::class);
        $driver = new IndexAttributeDriver();

        $result = $driver->loadClass(IndexedEntity::class);

        $this->assertEquals([
            'index' => [
                'index1' => [
                    'property' => 'text2',
                    'type' => 'text',
                ],
                'text1' => [
                    'property' => 'text1',
                    'type' => 'text',
                ]
            ]
        ], $result);

        $provider->provide($metadata, $result);

        $index = $metadata->getIndex();
        $this->assertEquals('index1', $index['index1'][0]->getKey());
        $this->assertEquals([
            'property' => 'text2',
            'type' => 'text'
        ], $index['index1'][0]->getConfig());

        $this->assertEquals('text1', $index['text1'][0]->getKey());
        $this->assertEquals([
            'property' => 'text1',
            'type' => 'text'
        ], $index['text1'][0]->getConfig());
    }
}

#[Index('text', ['name' => 'index1', 'property' => 'text2'])]
class IndexedEntity
{

    #[Index('text')]
    private $text1;
    private $text2;
}
