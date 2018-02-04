<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.02.18
 * Time: 17:38
 */

namespace Enhavo\Bundle\AppBundle\Filter;


use Enhavo\Bundle\AppBundle\Type\AbstractType;

abstract class AbstractFilter extends AbstractType implements FilterInterface
{
    public function getPermission($options)
    {
        return null;
    }
}