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

    public function validate($value, Constraint $constraint)
    {
        $translator = $this->container->get('translator');
        //check if there are any nodes and the name of the workflow is not null
        if(is_array($value)){
            //Nodes
            $no_nodes = true;
            foreach($value as $currentValue) {
                if(is_array($currentValue)) {
                    foreach ($currentValue as $val) {
                        if($val->getStart() || $val->getNodeName() == null){
                            continue;
                        } else {
                            $no_nodes = false;
                        }
                    }
                } else {
                    if($currentValue->getStart() || $currentValue->getNodeName() == null){
                        continue;
                    } else {
                        $no_nodes = false;
                    }
                }
            }
            if($no_nodes) {

                $this->context->buildViolation($translator->trans($constraint->noNodesCreated))
                    ->addViolation();
            }
        } else {
            //Name
            if($value == null) {
                $this->context->buildViolation($constraint->noWorkflowName)
                    ->addViolation();
            }
        }
    }
}