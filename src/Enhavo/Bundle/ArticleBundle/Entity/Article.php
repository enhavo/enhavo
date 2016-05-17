<?php
/**
 * Article.php
 *
 * @since 03/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;

class Article extends Content
{
    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $picture;

    /**
     * @var string
     */
    protected $teaser;

    /**
     * @var \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    protected $grid;

    /**
     * Set picture
     *
     * @param $picture \Enhavo\Bundle\MediaBundle\Entity\File|null
     * @return Article
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set teaser
     *
     * @param string $teaser
     * @return Article
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Grid $grid
     * @return Content
     */
    public function setGrid(\Enhavo\Bundle\GridBundle\Entity\Grid $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
}
