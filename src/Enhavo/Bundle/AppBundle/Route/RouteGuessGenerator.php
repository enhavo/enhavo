<?php
/**
 * RouteGuessGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

class RouteGuessGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $routeGuesser;

    public function __construct(RouteGuesser $routeGuesser)
    {
        $this->routeGuesser = $routeGuesser;
    }

    public function generate(Routeable $routeable)
    {
        return $this->routeGuesser->guessUrl($routeable);
    }
}