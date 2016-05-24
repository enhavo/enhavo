<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Workflow extends Constraint
{
    public $noNodesCreated = 'workflow.validator.error.nodes';
    public $doNotDeleteWhenActive = 'workflow.validator.error.delete';

    public function validatedBy()
    {
        return 'valid_workflow';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}