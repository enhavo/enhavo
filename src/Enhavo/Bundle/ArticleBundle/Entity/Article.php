<?php
/**
 * Article.php
 *
 * @since 03/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Enhavo\Bundle\ArticleBundle\Model\ArticleInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadAwareInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class Article extends Content implements ArticleInterface, ThreadAwareInterface
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
     * @var NodeInterface
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var ThreadInterface
     */
    private $thread;

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
     * @param NodeInterface $content
     * @return Content
     */
    public function setContent(NodeInterface $content = null)
    {
        $this->content = $content;
        if($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
        }
        return $this;
    }

    /**
     * Get content
     *
     * @return NodeInterface
     */
    public function getContent()
    {
        return $this->content;
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

    /**
     * @return ThreadInterface
     */
    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface $thread
     */
    public function setThread(?ThreadInterface $thread): void
    {
        $this->thread = $thread;
    }
}
