<?php


namespace Enhavo\Bundle\ArticleBundle\Factory;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\CommentBundle\Entity\Thread;
use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;

class ArticleFactory extends ContentFactory
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var RouteFactory
     */
    private $routeFactory;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    public function __construct(
        $className,
        NodeFactory $nodeFactory,
        RouteFactory $routeFactory,
        FileFactory $fileFactory)
    {
        parent::__construct($className);

        $this->nodeFactory = $nodeFactory;
        $this->routeFactory = $routeFactory;
        $this->fileFactory = $fileFactory;
    }

    /**
     * @param Article|null $originalResource
     * @return Article
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Article $originalResource */
        /** @var Article $newResource */
        $newResource = parent::duplicate($originalResource);
        $newResource->setPublic(false);
        
        $newResource->setTitle(sprintf('%s Copy', $originalResource->getTitle()));
        $newResource->setTeaser($originalResource->getTeaser());
        $newResource->setRoute($this->routeFactory->createNew());
        $newResource->setThread(new Thread());

        $newContainer = $this->nodeFactory->duplicate($originalResource->getContent());
        $newResource->setContent($newContainer);

        if($originalResource->getPicture()){
            $newPicture = $this->fileFactory->duplicate($originalResource->getPicture());
            $newResource->setPicture($newPicture);
        }

        return $newResource;
    }
}