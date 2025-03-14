<?php

namespace Enhavo\Bundle\PageBundle\Twig;

use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagePathExtension extends AbstractExtension
{
    public function __construct(
        private readonly TwigRouter $twigRouter
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('page_special_url', array($this, 'getPageSpecialUrl')),
            new TwigFunction('page_special_exists', array($this, 'getPageSpecialExists')),
        ];
    }

    public function getPageSpecialUrl($key, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        $path = sprintf('enhavo_page_page_special_%s', Slugifier::slugify($key));
        if ($this->twigRouter->exists($path)) {
            return $this->twigRouter->generate($path, $parameters, $referenceType);
        }
        return '#';
    }

    public function getPageSpecialExists($key): bool
    {
        $path = sprintf('enhavo_page_page_special_%s', Slugifier::slugify($key));
        return $this->twigRouter->exists($path);
    }
}
