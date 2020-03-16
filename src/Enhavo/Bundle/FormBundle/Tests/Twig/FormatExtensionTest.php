<?php

namespace Enhavo\Bundle\FormBundle\Tests\Twig;

use Enhavo\Bundle\FormBundle\Twig\FormatExtension;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function testFormatHeadline()
    {
        $sanitizer = new FormatExtension();

        $this->assertEquals(
            '<h1>Test</h1>',
            $sanitizer->formatHeadline('<h1>Test</h1>'),
            'No formation should be added'
        );

        $this->assertEquals(
            '<div>Test</div>',
            $sanitizer->formatHeadline('Test'),
            'Block formation should be added'
        );

        $this->assertEquals(
            '<h1 class="my-class">Test</h1>',
            $sanitizer->formatHeadline('<h1>Test</h1>', 'my-class'),
            'Additional class should appear'
        );

        $this->assertEquals(
            '<h1 class="my-class" data-additional="value">Test</h1>',
            $sanitizer->formatHeadline('<h1>Test</h1>', 'my-class', ['data-additional' => 'value']),
            'Additional attribute should appear'
        );

        $this->assertEquals(
            '<h1 class="my-class" data-additional="{&quot;key&quot;:&quot;value&quot;}">Test</h1>',
            $sanitizer->formatHeadline('<h1>Test</h1>', 'my-class', ['data-additional' => ['key' => 'value']]),
            'Array should be json encoded'
        );
    }
}
