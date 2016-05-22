<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowStatusInterface;

/**
 * Page
 */
class Page extends Content
{
    /**
     * @var GridInterface
     */
    protected $grid;

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
     * @var WorkflowStatusInterface
     */
    private $workflow_status;


    /**
     * Set workflowStatus
     *
     * @param WorkflowStatusInterface $workflowStatus
     *
     * @return Page
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
}
