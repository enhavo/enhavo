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

class TextSimplifyTest extends TestCase
{
    public function testSimplify()
    {
        $textSimplify = new TextSimplify();

        $this->assertEquals('lorem ipsum dolor', $textSimplify->simplify('Lorem ipsum! Dolor'));
        $this->assertEquals('lorem ipsum dolor', $textSimplify->simplify('Lorem   ipsum!!! Dolor'));
        $this->assertEquals('lorem ipsum dolor', $textSimplify->simplify('"Lorem" "ipsum" .Dolor?'));
        $this->assertEquals('lorem ipsum dolor', $textSimplify->simplify('"Lorem" (ipsum) .Dolor?'));
        $this->assertEquals('loräm ipsum dolör', $textSimplify->simplify('"LorÄm" ipsum dolör'));
    }
}
