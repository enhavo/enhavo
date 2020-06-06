<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-06
 * Time: 21:58
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
            ]
        ];

        $b = [
            'url' => 2,
            'level1' => [
                'a' => false,
                'c' => true,
            ]
        ];

        $result = ArrayUtil::merge($a, $b);

        $this->assertEquals([
            'url' => 2,
            'level1' => [
                'a' => false,
                'b' => true,
                'c' => true,
            ]
        ], $result);
    }
}
