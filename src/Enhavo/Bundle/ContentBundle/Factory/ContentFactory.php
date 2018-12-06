<?php
namespace Enhavo\Bundle\ContentBundle\Factory;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\AppBundle\Factory\Factory;

class ContentFactory extends Factory
{
    /**
     * @param Content|null $originalResource
     * @return Content
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Content $newResource */
        $newResource = $this->createNew();

        $newResource->setTitle($originalResource->getTitle());
        $newResource->setSlug($originalResource->getSlug());
        $newResource->setMetaDescription($originalResource->getMetaDescription());
        $newResource->setPageTitle($originalResource->getPageTitle());
        $newResource->setPriority($originalResource->getPriority());
        $newResource->setChangeFrequency($originalResource->getChangeFrequency());
        $newResource->setPublic($originalResource->isPublic());
        $newResource->setPublicationDate($originalResource->getPublicationDate());
        $newResource->setPublishedUntil($originalResource->getPublishedUntil());
        $newResource->setCreated(new \DateTime());
        $newResource->setUpdated(new \DateTime());

        return $newResource;
    }
}
