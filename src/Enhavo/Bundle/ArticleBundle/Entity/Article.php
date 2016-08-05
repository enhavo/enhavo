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
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowStatusInterface;

class Article extends Content
{
    /**
     * @var FileInterface
     */
    protected $picture;

    /**
     * @var string
     */
    protected $teaser;

    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var WorkflowStatusInterface
     */
    protected $workflow_status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set workflowStatus
     *
     * @param WorkflowStatusInterface $workflowStatus
     *
     * @return Article
     */
    public function setWorkflowStatus(WorkflowStatusInterface $workflowStatus = null)
    {
        $this->workflow_status = $workflowStatus;

        return $this;
    }

    /**
     * Get workflowStatus
     *
     * @return WorkflowStatusInterface
     */
    public function getWorkflowStatus()
    {
        return $this->workflow_status;
    }

    /**
     * Add category
     *
     * @param CategoryInterface $category
     *
     * @return Article
     */
    public function addCategory(CategoryInterface $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param CategoryInterface $category
     */
    public function removeCategory(CategoryInterface $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
