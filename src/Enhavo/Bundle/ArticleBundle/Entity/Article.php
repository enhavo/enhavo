<?php
/**
 * Article.php
 *
 * @since 03/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\CategoryBundle\Model\CategoryInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class Article extends Content
{
    /**
     * @var FileInterface
     */
    private $picture;

    /**
     * @var string
     */
    private $teaser;

    /**
     * @var GridInterface
     */
    private $grid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set picture
     *
     * @param $picture FileInterface|null
     * @return Article
     */
    public function setPicture(FileInterface $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return FileInterface|null
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
     * @param GridInterface $grid
     * @return Content
     */
    public function setGrid(GridInterface $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     *
     * @return GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Add category
     *
     * @param TermInterface $category
     *
     * @return Article
     */
    public function addCategory(TermInterface $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param TermInterface $category
     */
    public function removeCategory(TermInterface $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection|TermInterface[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add tag
     *
     * @param TermInterface $tag
     *
     * @return Article
     */
    public function addTag(TermInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param TermInterface $tag
     */
    public function removeTag(TermInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection|TermInterface[]
     */
    public function getTags()
    {
        return $this->tags;
    }
}
