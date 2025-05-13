<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
            'Dolor',
        ], $textToWord->split('Lorem ipsum! Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum...Dolor',
        ], $textToWord->split('Lorem ipsum...Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum',
            'Dolor',
        ], $textToWord->split('Lorem ipsum   Dolor'));

        $this->assertSame([
            'Lorem',
            'ipsum',
            'Dolor',
        ], $textToWord->split('Lorem ipsum   Dolor'));
    }
}
