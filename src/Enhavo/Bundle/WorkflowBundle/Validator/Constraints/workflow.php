<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Workflow extends Constraint
{
    public $noNodesCreated = 'You have not created any nodes.';

    public function validatedBy()
    {
        return 'no_nodes_created';
    }
}