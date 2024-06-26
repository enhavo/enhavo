<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-03-29
 * Time: 11:58
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use PHPUnit\Framework\TestCase;

abstract class AbstractTypeTestCase extends TestCase
{
    /**
     * @param $type
     * @param $options
     * @return Column
     */
    public function createColumn($type, $options)
    {
        return new Column($type, $options);
    }
}
