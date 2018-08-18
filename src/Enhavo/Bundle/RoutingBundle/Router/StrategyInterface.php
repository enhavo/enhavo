<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:32
 */

namespace Enhavo\Bundle\RoutingBundle\Router;


interface StrategyInterface
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);
}