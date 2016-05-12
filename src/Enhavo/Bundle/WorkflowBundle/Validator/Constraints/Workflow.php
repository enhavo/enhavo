<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Workflow extends Constraint
{
    public $noNodesCreated = 'workflow.validator.error.nodes';
    public $noWorkflowName = 'workflow.validator.error.name';

    public function validatedBy()
    {
        return 'valid_workflow';
    }
}