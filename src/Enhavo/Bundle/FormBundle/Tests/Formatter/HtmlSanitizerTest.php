<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-03-16
 * Time: 11:08
 */

namespace Enhavo\Bundle\FormBundle\Tests\Formatter;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\FormBundle\Formatter\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function testSanitize()
    {
        $sanitizer = new HtmlSanitizer(sys_get_temp_dir().'/purifier/serialization', new Filesystem());

        $this->assertEquals('<p>Test</p>', $sanitizer->sanitize('<script>Hello</script><p>Test</p>'));
        $this->assertEquals('Hello<p>Test</p>', $sanitizer->sanitize('<iframe>Hello</iframe><p>Test</p>'));
        $this->assertEquals('Test', $sanitizer->sanitize('Test'));
    }
}
