<?php

namespace Enhavo\Bundle\AppBundle\Config;

use Enhavo\Bundle\AppBundle\Form\Config\WysiwygOption;

class WysiwygOptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $option = new WysiwygOption();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Form\Config\WysiwygOption', $option);
    }

    public function testFormats()
    {
        $defaultFormats = [
            [
                'title' => 'hello',
                'styles' => 'styles',
                'inline' => 'span'
            ],
            [
                'title' => 'hello2',
                'styles' => 'styles',
                'inline' => 'b'
            ],
        ];

        $option = new WysiwygOption();

        $formats = $option->getFormats($defaultFormats);
        $this->assertTrue(is_array($formats), 'Format should be an array');
        $this->assertCount(2, $formats);

        $option->setFormats(['hello']);
        $formats = $option->getFormats($defaultFormats);
        $this->assertTrue(is_array($formats), 'Format should be an array');
        $this->assertCount(1, $formats);
        $this->assertArrayHasKey('title', $formats[0]);
        $this->assertEquals('hello', $formats[0]['title']);
    }

    public function testGetAndSetHeight()
    {
        $option = new WysiwygOption();

        $this->assertEquals(200, $option->getHeight(200));

        $option->setHeight(100);
        $this->assertEquals(100, $option->getHeight());
    }

    public function testGetAndSetToolbar1()
    {
        $option = new WysiwygOption();

        $this->assertEquals('toolbar', $option->getToolbar1('toolbar'));

        $option->setToolbar1('other toolbar');
        $this->assertEquals('other toolbar', $option->getToolbar1());
    }

    public function testGetAndSetToolbar2()
    {
        $option = new WysiwygOption();

        $this->assertEquals('toolbar', $option->getToolbar2('toolbar'));

        $option->setToolbar2('other toolbar');
        $this->assertEquals('other toolbar', $option->getToolbar2());
    }

    public function testGetAndSetContentCss()
    {
        $option = new WysiwygOption();

        $this->assertArraySubset(array('/bundles/enhavoapp/css/editor.css','/bundles/path/'), $option->getContentCss('path'));
        $this->assertArraySubset(array('/bundles/enhavoapp/css/editor.css'),$option->getContentCss());

        $option->setContentCss(['path1', 'path2']);
        $this->assertArraySubset(['/bundles/enhavoapp/css/editor.css','/bundles/path1/', '/bundles/path2/'], $option->getContentCss());
    }
}