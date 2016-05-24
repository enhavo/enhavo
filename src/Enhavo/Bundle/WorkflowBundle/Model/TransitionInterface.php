<?php
/**
 * TransitionInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\WorkflowBundle\Model;


interface TransitionInterface
{
    public function getWorkflow();

    public function setWorkflow(WorkflowInterface $workflow);
}