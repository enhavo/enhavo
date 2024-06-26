<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 20:36
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\View;

use Enhavo\Bundle\AppBundle\View\ViewData;
use PHPUnit\Framework\TestCase;

class ViewDataTest extends TestCase
{
    private function create()
    {
        $viewData = new ViewData();
        $viewData->set('hello', 'world');
        $viewData->set('foo', 'bar');
        return $viewData;
    }

    public function testIterable()
    {
        $viewData = $this->create();

        $i = 0;
        foreach ($viewData as $key => $value) {
            if($i == 0) {
                $this->assertEquals('hello', $key);
                $this->assertEquals('world', $value);
            } else {
                $this->assertEquals('foo', $key);
                $this->assertEquals('bar', $value);
            }
            $i++;
        }
    }

    public function testArrayAccess()
    {
        $viewData = $this->create();

        $viewData[4] = 2;
        $viewData[] = 42;

        $this->assertCount(4, $viewData);
        $this->assertEquals('world', $viewData['hello']);
        $this->assertTrue(isset($viewData['hello']));
        $this->assertFalse(isset($viewData['something']));

        unset($viewData[4]);
        $this->assertFalse(isset($viewData[4]));
    }

    public function testGetterAndSetter()
    {
        $viewData = $this->create();

        $this->assertEquals('bar', $viewData->get('foo'));
        $this->assertEquals('anything', $viewData->get('something', 'anything'));
        $this->assertTrue($viewData->has('hello'));

        $viewData->remove('hello');

        $this->assertFalse($viewData->has('hello'));

        $viewData->replace([
            'key' => 'value'
        ]);

        $this->assertEquals([
            'key' => 'value'
        ], $viewData->normalize());

        $viewData->add(['hello' => 'world!']);

        $this->assertEquals([
            'key' => 'value',
            'hello' => 'world!'
        ], $viewData->normalize());
    }

    public function testNormalize()
    {
        $viewData = $this->create();

        $this->assertEquals([
            'hello' => 'world',
            'foo' => 'bar',
        ], $viewData->normalize());
    }

    public function testKeys()
    {
        $viewData = $this->create();
        $this->assertEquals(['hello', 'foo'], $viewData->keys());
    }
}
