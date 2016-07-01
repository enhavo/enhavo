<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowStatusInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Newsletter
 */
class Newsletter implements ResourceInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var boolean
     */
    protected $sent;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Newsletter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Newsletter
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sent
     *
     * @param boolean $sent
     *
     * @return Newsletter
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return boolean
     */
    public function getSent()
    {
        return $this->sent;
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
     * @return Newsletter
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
