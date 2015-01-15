<?php
/**
 * RouteEvent.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use esperanto\AdminBundle\Builder\SyliusBuilder;

class BuilderEvent extends Event
{
    protected $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return SyliusBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }
}