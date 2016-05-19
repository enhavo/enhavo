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
     * @var \Enhavo\Bundle\GridBundle\Entity\Grid
     */
    protected $grid;

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
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus
     */
    private $workflow_status;


    /**
     * Set workflowStatus
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus $workflowStatus
     *
     * @return Page
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
