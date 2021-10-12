<?php

namespace Enhavo\Bundle\PageBundle\Twig;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagePathExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $pageRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RepositoryInterface $pageRepository, RouterInterface $router)
    {
        $this->pageRepository =  $pageRepository;
        $this->router = $router;
    }

    public function getGlobals()
    {
        return array();
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('page_path', array($this, 'getPagePath')),
        );
    }

    public function getPagePath($code, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $page = $this->pageRepository->findOneBy([
            'code' => $code
        ]);

        if(!$page instanceof Page) {
            return $this->getDefaultLink($code);
        }

        if($page->getRoute() === null) {
            return $this->getDefaultLink($code);
        }

        try {
            return $this->router->generate($page->getRoute(), $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            return $this->getDefaultLink($code);
        }
    }

    protected function getDefaultLink($code)
    {
        return sprintf('#%s', $code);
    }

    public function getName()
    {
        return 'page_path_extension';
    }
}
