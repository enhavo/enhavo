<?php
namespace Enhavo\Bundle\WorkflowBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkflowValidator extends ConstraintValidator
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function validate($value, Constraint $constraint)
    {
        if(is_array($value)){
            //Nodes
            $no_nodes = true;
            foreach($value as $currentValue) {
                if(is_array($currentValue)) {
                    foreach ($currentValue as $val) {
                        if($val->getStart() == true || $val->getNodeName() == null){
                            continue;
                        } else {
                            $no_nodes = false;
                        }
                    }
                } else {
                    if($currentValue->getStart() == true || $currentValue->getNodeName() == null){
                        continue;
                    } else {
                        $no_nodes = false;
                    }
                }
            }
            if($no_nodes) {
                $this->context->buildViolation($constraint->noNodesCreated)
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