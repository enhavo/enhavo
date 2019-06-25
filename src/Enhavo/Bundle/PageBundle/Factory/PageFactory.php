<?php
namespace Enhavo\Bundle\PageBundle\Factory;

use Enhavo\Bundle\BlockBundle\Factory\ContainerFactory;
use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageFactory extends ContentFactory
{
    /**
     * @var ContainerFactory
     */
    protected $nodeFactory;

    public function __construct($className, NodeFactory $nodeFactory)
    {
        parent::__construct($className);

        $this->nodeFactory = $nodeFactory;
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

        $newContainer = $this->nodeFactory->duplicate($originalResource->getContent());
        $newPage->setContent($newContainer);

        $newPage->setParent($originalResource->getParent());

        return $newPage;
    }
}
