<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Tab;

use Enhavo\Bundle\ResourceBundle\Tab\ArrangementParser;
use PHPUnit\Framework\TestCase;

class ArrangementParserTest extends TestCase
{
    public function testNull()
    {
        $this->assertSame([], (new ArrangementParser())->parse(null));
    }

    public function testStringWithRowsColumnsAndSizes()
    {
        $this->assertSame([
            [['key' => 'name', 'size' => 1], ['key' => 'title', 'size' => 2]],
            [['key' => 'text', 'size' => 1]],
        ], (new ArrangementParser())->parse("name | title{2}\ntext"));
    }

    public function testArrayOfLines()
    {
        $this->assertSame([
            [['key' => 'name', 'size' => 1]],
            [['key' => 'text', 'size' => 3]],
        ], (new ArrangementParser())->parse(['name', 'text{3}']));
    }

    public function testColumnWithoutPositiveSizeIsSkipped()
    {
        $this->assertSame([
            [['key' => 'text', 'size' => 1]],
        ], (new ArrangementParser())->parse('name{0} | text'));
    }
}