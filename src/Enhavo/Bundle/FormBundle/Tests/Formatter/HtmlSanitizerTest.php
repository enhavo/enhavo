<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Formatter;

use Enhavo\Bundle\FormBundle\Formatter\HtmlSanitizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class HtmlSanitizerTest extends TestCase
{
    public function testSanitize()
    {
        $sanitizer = new HtmlSanitizer(sys_get_temp_dir().'/purifier/serialization', new Filesystem());

        $this->assertEquals('<p>Test</p>', $sanitizer->sanitize('<script>Hello</script><p>Test</p>'));
        $this->assertEquals('Hello<p>Test</p>', $sanitizer->sanitize('<iframe>Hello</iframe><p>Test</p>'));
        $this->assertEquals('Test', $sanitizer->sanitize('Test'));
        $this->assertEquals('<a href="url" target="_blank" rel="noreferrer noopener">Test</a>', $sanitizer->sanitize('<a href="url" target="_blank">Test</a>'));
    }
}
