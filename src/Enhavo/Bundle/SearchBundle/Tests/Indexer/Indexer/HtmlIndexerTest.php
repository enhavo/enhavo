<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 20:12
 */

namespace Enhavo\Bundle\SearchBundle\Indexer\Indexer;

use Enhavo\Bundle\SearchBundle\Indexer\IndexData;
use PHPUnit\Framework\TestCase;

class HtmlIndexerTest extends TestCase
{
    public function testGetIndexes()
    {
        $htmlIndexer = new HtmlIndexer();
        $value = "<p><h1>Header</h1><h2>Header2</h2>Lorem Ipsum dolor </p> Hallo";
        $indexes = $htmlIndexer->getIndexes($value, []);

        $this->assertCount(4, $indexes);
        /** @var IndexData $index */
        $index = $indexes[0];
        $this->assertEquals('Header', $index->getValue());
        $this->assertEquals(25, $index->getWeight());

        $index = $indexes[1];
        $this->assertEquals('Header2', $index->getValue());
        $this->assertEquals(18, $index->getWeight());

        $index = $indexes[2];
        $this->assertEquals('Lorem Ipsum dolor', $index->getValue());
        $this->assertEquals(1, $index->getWeight());

        $index = $indexes[3];
        $this->assertEquals('Hallo', $index->getValue());
        $this->assertEquals(1, $index->getWeight());
    }
}
