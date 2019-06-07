<?php
namespace Enhavo\Bundle\PageBundle\Factory;

use Enhavo\Bundle\BlockBundle\Factory\ContainerFactory;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageFactory extends ContentFactory
{
    /**
     * @var ContainerFactory
     */
    protected $containerFactory;

    public function __construct($className, ContainerFactory $containerFactory)
    {
        parent::__construct($className);

        $this->containerFactory = $containerFactory;
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

        $newContainer = $this->containerFactory->duplicate($originalResource->getContainer());
        $newPage->setContainer($newContainer);

        $newPage->setParent($originalResource->getParent());

        return $newPage;
    }
}
