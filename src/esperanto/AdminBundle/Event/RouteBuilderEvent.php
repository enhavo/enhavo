<?php
/**
 * RouteEvent.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Event;

use esperanto\AdminBundle\Builder\Route\SyliusRouteBuilder;

class RouteBuilderEvent extends BuilderEvent
{
    /**
     * @return SyliusRouteBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}