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
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus
     */
    private $workflow_status;

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

    /**
     * Set workflowStatus
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus $workflowStatus
     *
     * @return Article
     */
    public function setWorkflowStatus(\Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus $workflowStatus = null)
    {
        $this->workflow_status = $workflowStatus;

        return $this;
    }

    /**
     * Get workflowStatus
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus
     */
    public function getWorkflowStatus()
    {
        return $this->workflow_status;
    }
}
