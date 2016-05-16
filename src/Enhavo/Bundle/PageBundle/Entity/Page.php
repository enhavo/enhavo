<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;

/**
 * Page
 */
class Page extends Content
{
    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Content
     */
    protected $content;

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Content $content
     * @return Content
     */
    public function setContent(\Enhavo\Bundle\GridBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}
