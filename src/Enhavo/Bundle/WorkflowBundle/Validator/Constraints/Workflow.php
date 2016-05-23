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
    public $noNodeName = 'workflow.validator.error.node.name';
    public $noTransitionName = 'workflow.validator.error.transition.name';
    public $sameNodes = 'workflow.validator.error.transition.sameNodes';

    public function validatedBy()
    {
        return 'valid_workflow';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}