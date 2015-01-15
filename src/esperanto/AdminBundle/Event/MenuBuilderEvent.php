<?php
/**
 * RouteEvent.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Event;

use esperanto\AdminBundle\Builder\Menu\MenuBuilder;

class MenuBuilderEvent extends BuilderEvent
{
    /**
     * @return MenuBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}