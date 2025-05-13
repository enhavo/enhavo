<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Twig;

use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RouterExtension constructor.
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('router', [$this, 'generate']),
        ];
    }

    /**
     * @param array  $parameters
     * @param int    $referenceType
     * @param string $type
     *
     * @return string
     */
    public function generate($resource, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $type = 'default')
    {
        return $this->router->generate($resource, $parameters, $referenceType, $type);
    }
}
