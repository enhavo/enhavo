<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 11.05.16
 * Time: 12:40
 */

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;

class WorkflowCreateVoter implements VoterInterface {

    protected $manager;
    protected $container;

    public function __construct(ObjectManager $manager, $container)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!in_array('ROLE_ENHAVO_WORKFLOW_WORKFLOW_CREATE', $attributes)) {
            return self::ACCESS_ABSTAIN;
        }

        //check if there are still possible entities without workflows --> if there are, display the create-button
        $workflowRepository = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow');
        $workflows = $workflowRepository->findAll();
        $possibleEntities = $this->container->getParameter('enhavo_workflow.entities');
        $entities = array();
        foreach ($possibleEntities as $possibleEntityData) {
            $entityName = $possibleEntityData['class'];
            $entityHasWF = false;
            foreach ($workflows as $workflow) {
                if ($workflow->getEntity() == $entityName) {
                    $entityHasWF = true;
                }
            }
            if ($entityHasWF == false) {
                $entities[] = $entityName;
            }
        }
        if (empty($entities)) {
            return VoterInterface::ACCESS_DENIED;
        }
        return VoterInterface::ACCESS_GRANTED;
    }
}