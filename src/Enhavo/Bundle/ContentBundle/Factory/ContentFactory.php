<?php
namespace Enhavo\Bundle\ContentBundle\Factory;

use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;

class ContentFactory extends Factory
{
    /**
     * @return Content
     */
    public function createNew(): Content
    {
        /** @var Content $resource */
        $resource = parent::createNew();
        $resource->setCreatedAt(new \DateTime());
        $resource->setUpdatedAt(new \DateTime());

        return $resource;
    }
}
