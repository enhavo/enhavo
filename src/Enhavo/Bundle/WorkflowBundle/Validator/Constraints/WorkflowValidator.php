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
        $no_nodes = true;
        foreach($value as $currentValue) {
            if($currentValue->getNodeName() == 'creation' || $currentValue->getNodeName() == null){
                continue;
            } else {
                $no_nodes = false;
            }
        }
        if($no_nodes) {
            $this->context->buildViolation($constraint->noNodesCreated)
                ->addViolation();
        }
    }
}