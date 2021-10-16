<?php

namespace Enhavo\Bundle\PageBundle\Twig;

use Enhavo\Bundle\PageBundle\Page\PageManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagePathExtension extends AbstractExtension
{
    /** @var PageManager */
    private $pageManager;

    /**
     * PagePathExtension constructor.
     * @param PageManager $pageManager
     */
    public function __construct(PageManager $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('page_path', array($this, 'getPagePath')),
        );
    }

    public function getPagePath($code, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->pageManager->getPagePath($code, $parameters, $referenceType);
    }
}
