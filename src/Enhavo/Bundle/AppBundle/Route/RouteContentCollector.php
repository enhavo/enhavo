<?php
/**
 * RouteContentCollector.php
 *
 * @since 18/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Route;


use Doctrine\Common\Collections\ArrayCollection;
use enhavo\AdminBundle\Route\RouteContentLoader;

class RouteContentCollector
{
    /**
     * @var ArrayCollection
     */
    private $collection;

    public function __construct()
    {
        $this->collection = array();
    }

    public function add(RouteContentLoader $loader)
    {
        $this->collection[] = $loader;
    }

    public function getCollection()
    {
        return $this->collection;
    }
}