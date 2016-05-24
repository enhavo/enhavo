<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\Container;

class WorkflowValidator extends ConstraintValidator
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function validate($workflowClass, Constraint $constraint)
    {
        $translator = $this->container->get('translator');
        $this->validateCreatedNodes($workflowClass->getFormNodes(), $translator, $constraint);
        if($workflowClass->getActive()){
            $this->validateDeletedNodes($workflowClass->getFormNodes(), $workflowClass->getNodes(), $translator, $constraint);
        }
        if($workflowClass->getTransitions() != null){
            $this->validateTransitions($workflowClass->getTransitions(), $translator, $constraint);
        }
    }

    protected function validateCreatedNodes($nodes, $translator, $constraint)
    {
        //check if there are any nodes and the name of the workflow is not null
        //Nodes
        $no_nodes = true;
        foreach($nodes as $currentValue) {
            if(is_array($currentValue)) {
                foreach ($currentValue as $val) {
                    if($val->getStart()){

                        //start node can be skipped
                        continue;
                    } else if($val->getName() == null){

                        //give every node a name
                        $this->context->buildViolation($translator->trans($constraint->noNodeName, array(), 'EnhavoWorkflowBundle'))
                            ->addViolation();
                    } else {

                        //there is a node
                        $no_nodes = false;
                    }
                }
            } else {
                if($currentValue->getStart()){

                    //start node can be skipped
                    continue;
                } else if($currentValue->getName() == null){

                    //give every node a name
                    $this->context->buildViolation($translator->trans($constraint->noNodeName, array(), 'EnhavoWorkflowBundle'))
                        ->addViolation();
                } else {

                    //there is a node
                    $no_nodes = false;
                }
            }
        }
        if($no_nodes) {

            //there are no nodes
            $this->context->buildViolation($translator->trans($constraint->noNodesCreated, array(), 'EnhavoWorkflowBundle'))
                ->addViolation();
        }
    }

    protected function validateDeletedNodes($formNodes, $nodes, $translator, $constraint)
    {
        unset($nodes[0]);
        $formNodes = array_values($formNodes);
        $nodesArray = array();
        foreach ($nodes as $node) {
            $nodesArray[] = $node;
        }
        if(count($nodesArray) == count($formNodes)){
            for($i = 0; $i < count($nodesArray); $i++){
                if($nodesArray[$i] != $formNodes[$i]){
                    $this->context->buildViolation($translator->trans($constraint->doNotDeleteWhenActive, array(), 'EnhavoWorkflowBundle'))
                        ->addViolation();
                    return;
                }
            }
        } else {
            $this->context->buildViolation($translator->trans($constraint->doNotDeleteWhenActive, array(), 'EnhavoWorkflowBundle'))
                ->addViolation();
            return;
        }

    }

    protected function validateTransitions($transitions, $translator, $constraint)
    {

        foreach ($transitions as $transition) {

            //check if the name is empty
            if(!$transition->getName()){
                $this->context->buildViolation($translator->trans($constraint->noTransitionName, array(), 'EnhavoWorkflowBundle'))
                    ->addViolation();
            }

            //check if the transition gos from one node to another node
            if($transition->getNodeFrom() == $transition->getNodeTo()){
                $this->context->buildViolation($translator->trans($constraint->sameNodes, array(), 'EnhavoWorkflowBundle'))
                    ->setParameter('%transitionName%', $transition->getName())
                    ->addViolation();
            }
        }

    }
}