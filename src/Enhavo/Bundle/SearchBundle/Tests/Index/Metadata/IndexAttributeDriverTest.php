<?php

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;

use Enhavo\Bundle\SearchBundle\Tests\Mock\ModelMock;
use PHPUnit\Framework\TestCase;

class IndexAttributeDriverTest extends TestCase
{
    public function testLoadClass(): void
    {
        $driver = new IndexAttributeDriver();

        $result = $driver->loadClass(ModelMock::class);

        $this->assertEquals([
            'index' => [
                'index1' => [
                    'property' => 'text2',
                    'type' => 'text',
                ],
                'text' => [
                    'property' => 'text',
                    'type' => 'text',
                ]
            ]
        ], $result);
    }
}
