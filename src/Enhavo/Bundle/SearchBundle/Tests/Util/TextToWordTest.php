<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.08.18
 * Time: 02:11
 */

namespace Enhavo\Bundle\SearchBundle\Util;
use PHPUnit\Framework\TestCase;

class TextToWordTest extends TestCase
{
    public function testSplit()
    {
        $textToWord = new TextToWord();

        $this->assertSame([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum! Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum...Dolor',
        ], $textToWord->split('Lorem ipsum...Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum   Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum   Dolor'));
    }
}
