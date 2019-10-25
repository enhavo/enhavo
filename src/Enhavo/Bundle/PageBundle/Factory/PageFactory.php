<?php
namespace Enhavo\Bundle\PageBundle\Factory;

use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;

class PageFactory extends ContentFactory
{

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var RouteFactory
     */
    private $routeFactory;

    public function __construct($className, NodeFactory $nodeFactory, RouteFactory $routeFactory)
    {
        parent::__construct($className);

        $this->nodeFactory = $nodeFactory;
        $this->routeFactory = $routeFactory;
    }

    /**
     * @param Content|null $originalResource
     * @return Page
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Page $originalResource */
        /** @var Page $newPage */
        $newPage = parent::duplicate($originalResource);

        $newPage->setTitle($originalResource->getTitle() . ' (2)');
        $newPage->setPublic(false);
        $newPage->setCode(null);
        $newPage->setRoute($this->routeFactory->createNew());

        $newContainer = $this->nodeFactory->duplicate($originalResource->getContent());
        $newPage->setContent($newContainer);

        $newPage->setParent(null);

        return $newPage;
    }
}
