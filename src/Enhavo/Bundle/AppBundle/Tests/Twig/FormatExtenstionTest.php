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
}
