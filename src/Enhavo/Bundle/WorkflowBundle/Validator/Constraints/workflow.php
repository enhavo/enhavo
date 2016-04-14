<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Workflow extends Constraint
{
    public $noNodesCreated = 'You have not created any nodes.';
    public $noWorkflowName = 'Please set a name for the workflow.';

    public function validatedBy()
    {
        return 'valid_workflow';
    }
}