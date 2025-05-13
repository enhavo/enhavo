<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Tests\Util;

use Enhavo\Bundle\AppBundle\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    public function testMerge()
    {
        $a = [
            'url' => 1,
            'level1' => [
                'a' => true,
                'b' => true,
            ],
        ];

        $b = [
            'url' => 2,
            'level1' => [
                'a' => false,
                'c' => true,
            ],
        ];

        $result = ArrayUtil::merge($a, $b);

        $this->assertEquals([
            'url' => 2,
            'level1' => [
                'a' => false,
                'b' => true,
                'c' => true,
            ],
        ], $result);
    }
}
