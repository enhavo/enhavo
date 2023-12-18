<?php

namespace Enhavo\Bundle\AppBundle\Tests\Endpoint\Template;

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class LoaderTest extends TestCase
{
    private function createLoader()
    {
        return new Loader(__DIR__ . '/../../Fixtures/data', new ExpressionLanguage());
    }

    public function testLoadYaml()
    {
        $loader = $this->createLoader();
        $data = $loader->load('test.yaml');

        $this->assertEquals([
            'message' => 'Hello World!'
        ], $data);
    }

    public function testLoadJson()
    {
        $loader = $this->createLoader();
        $data = $loader->load('test.json');

        $this->assertEquals([
            'message' => 'Hello World!'
        ], $data);
    }

    public function testLoadPHP()
    {
        $loader = $this->createLoader();
        $data = $loader->load('test.php');

        $this->assertEquals([
            'message' => 'Hello World!'
        ], $data);
    }

    public function testLoaderInPHPFile()
    {
        $loader = $this->createLoader();
        $data = $loader->load('test-loader.php');

        $this->assertEquals([
            'message' => 'Hello World!'
        ], $data);
    }

    public function testMerge()
    {
        $loader = $this->createLoader();

        $target = [
            'key1' => 'value',
            'key2' => [
                'key2_1' => 'deep1'
            ]
        ];

        $source = [
            'key1' => 'other',
            'key3' => [
                'key3_1' => 'deep1'
            ],
        ];

        $loader->merge($target, $source);

        $this->assertEquals([
            'key1' => 'other',
            'key2' => [
                'key2_1' => 'deep1'
            ],
            'key3' => [
                'key3_1' => 'deep1'
            ],
        ], $target);
    }

    public function testRecursive()
    {
        $loader = $this->createLoader();

        $target = [
            'key1' => 'value',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'hello',
                    'key2_2_2' => 'other',
                ]
            ]
        ];

        $source = [
            'key1' => 'other',
            'key2' => [
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ],
        ];

        $loader->merge($target, $source, true);

        $this->assertEquals([
            'key1' => 'other',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_2' => 'other',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ]
        ], $target);
    }

    public function testRecursiveDepth1()
    {
        $loader = $this->createLoader();

        $target = [
            'key1' => 'value',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'hello',
                    'key2_2_2' => 'other',
                ]
            ]
        ];

        $source = [
            'key1' => 'other',
            'key2' => [
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ],
        ];

        $loader->merge($target, $source, true, 1);

        $this->assertEquals([
            'key1' => 'other',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ]
        ], $target);
    }

    public function testRecursiveDepth0()
    {
        $loader = $this->createLoader();

        $target = [
            'key1' => 'value',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'hello',
                    'key2_2_2' => 'other',
                ]
            ]
        ];

        $source = [
            'key1' => 'other',
            'key2' => [
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ],
        ];

        $loader->merge($target, $source, true, 0);

        $this->assertEquals([
            'key1' => 'other',
            'key2' => [
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ],
        ], $target);
    }

    public function testMergeArrayAccess()
    {
        $loader = $this->createLoader();

        $target = new Data([
            'key1' => 'value',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'hello',
                    'key2_2_2' => 'other',
                ]
            ]
        ]);

        $source = new Data([
            'key1' => 'other',
            'key2' => [
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ],
        ]);

        $loader->merge($target, $source, true);

        $this->assertEquals([
            'key1' => 'other',
            'key2' => [
                'key2_1' => 'deep1',
                'key2_2' => [
                    'key2_2_1' => 'something',
                    'key2_2_2' => 'other',
                    'key2_2_3' => 'world',
                ],
                'key2_3' => 'deep11',
                'key2_4' => 'deep111'
            ]
        ], $target->data);
    }
}

class Data implements \IteratorAggregate, \ArrayAccess
{
    public function __construct(
        public $data = [],
    )
    {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset] ?? null;
    }
}
