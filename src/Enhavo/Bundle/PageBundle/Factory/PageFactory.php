<?php
namespace Enhavo\Bundle\PageBundle\Factory;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;
use Enhavo\Bundle\GridBundle\Factory\GridFactory;
use Enhavo\Bundle\PageBundle\Entity\Page;

class PageFactory extends ContentFactory
{
    /**
     * @var GridFactory
     */
    protected $gridFactory;

    public function __construct($className, GridFactory $gridFactory)
    {
        parent::__construct($className);

        $this->gridFactory = $gridFactory;
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
        $newPage->setCode($originalResource->getCode());

        $newGrid = $this->gridFactory->duplicate($originalResource->getGrid());
        $newPage->setGrid($newGrid);

        //TODO: workflow_status

        return $newPage;
    }
}
