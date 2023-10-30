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
            new TwigFunction('page_path', array($this, 'getPagePath')),
            new TwigFunction('page_path_exists', array($this, 'existsPagePath')),
        ];
    }

    public function getPagePath($code, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $path = sprintf('enhavo_page_page_code_%s', Slugifier::slugify($code));
        if ($this->twigRouter->exists($path)) {
            return $this->twigRouter->generate($path, $parameters, $referenceType);
        }
        return '#';
    }

    public function existsPagePath($code)
    {
        $path = sprintf('enhavo_page_page_code_%s', Slugifier::slugify($code));
        return $this->twigRouter->exists($path);
    }
}
