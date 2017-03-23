<?php
/**
 * SlugifierTest.php
 *
 * @since 23/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Slugifier;


class SlugifierTest extends \PHPUnit_Framework_TestCase
{
    public function testSlugify()
    {
        $slugifier = new Slugifier();
        $this->assertEquals('this-is-a-test-string', $slugifier->slugify('This is a test string'));
        $this->assertEquals('uebel-weiss', $slugifier->slugify('Übel weiß'));
        $this->assertEquals('no-need', Slugifier::slugify('no$need'));
    }
}