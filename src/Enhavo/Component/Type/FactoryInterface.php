<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 15:35
 */

namespace Enhavo\Component\Type;


interface FactoryInterface
{
    public function create(array $options);
}
