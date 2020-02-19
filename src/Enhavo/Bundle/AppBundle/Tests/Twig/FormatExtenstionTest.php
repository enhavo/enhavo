<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-12
 * Time: 13:24
 */

namespace Enhavo\Bundle\AppBundle\Tests\Twig;

use Enhavo\Bundle\FormBundle\Twig\FormatExtension;
use PHPUnit\Framework\TestCase;

class FormatExtensionTest extends TestCase
{
    public function testSanitizeHtml()
    {
        $extension = new FormatExtension();

        $this->assertEquals('<p>Test</p>', $extension->sanitizeHtml('<script>Hello</script><p>Test</p>'));
        $this->assertEquals('<p></p><p>Test</p>', $extension->sanitizeHtml('<p><iframe>Hello</iframe></p><p>Test</p>'));
        $this->assertEquals('<p>Test</p>', $extension->sanitizeHtml('Test'));
    }

    public function testFormatHeadline()
    {
        $extension = new FormatExtension();

        $this->assertEquals(
            '<h1>Test</h1>',
            $extension->formatHeadline('<h1>Test</h1>'),
            'No formation should be added'
        );

        $this->assertEquals(
            '<div>Test</div>',
            $extension->formatHeadline('Test'),
            'Block formation should be added'
        );

        $this->assertEquals(
            '<h1 class="my-class">Test</h1>',
            $extension->formatHeadline('<h1>Test</h1>', 'my-class'),
            'Additional class should appear'
        );

        $this->assertEquals(
            '<h1 class="my-class" data-additional="value">Test</h1>',
            $extension->formatHeadline('<h1>Test</h1>', 'my-class', ['data-additional' => 'value']),
            'Additional attribute should appear'
        );

        $this->assertEquals(
            '<h1 class="my-class" data-additional="{&quot;key&quot;:&quot;value&quot;}">Test</h1>',
            $extension->formatHeadline('<h1>Test</h1>', 'my-class', ['data-additional' => ['key' => 'value']]),
            'Array should be json encoded'
        );
    }
}
