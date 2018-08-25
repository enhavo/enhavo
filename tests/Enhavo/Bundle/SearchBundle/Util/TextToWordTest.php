<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.08.18
 * Time: 02:11
 */

namespace Enhavo\Bundle\SearchBundle\Util;

class TextToWordTest extends \PHPUnit_Framework_TestCase
{
    public function testSplit()
    {
        $textToWord = new TextToWord();

        $this->assertArraySubset([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum! Dolor'));

        $this->assertArraySubset([
            'Lorem',
            'ipsum...Dolor',
        ], $textToWord->split('Lorem ipsum...Dolor'));

        $this->assertArraySubset([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum   Dolor'));

        $this->assertArraySubset([
            'Lorem',
            'ipsum',
            'Dolor'
        ], $textToWord->split('Lorem ipsum   Dolor'));
    }
}