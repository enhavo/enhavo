<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    public function __construct(
        private readonly TwigRouter $router,
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('router_generate', [$this, 'getRoutes']),
        ];
    }

    public function generate(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        return $this->router->generate($name, $parameters, $referenceType);
    }
}
