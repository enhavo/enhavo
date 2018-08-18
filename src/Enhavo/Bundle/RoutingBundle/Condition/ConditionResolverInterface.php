<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 01:12
 */

namespace Enhavo\Bundle\RoutingBundle\Condition;


interface ConditionResolverInterface
{
    public function resolve();
}