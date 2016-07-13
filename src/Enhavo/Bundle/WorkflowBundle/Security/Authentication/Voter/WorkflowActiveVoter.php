<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 11.05.16
 * Time: 14:42
 */

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * checks if the workflow section in the form should be displayed
 */
class WorkflowActiveVoter implements VoterInterface {

    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function supportsAttribute($attribute)
    {
        // if the attribute isn't one we support, return false
        if ($attribute != 'WORKFLOW_ACTIVE') {
            return false;
        }

        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(in_array('WORKFLOW_ACTIVE', $attributes)){
            //check if the current workflow is active
            $repository = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow');
            if ($repository->hasActiveWorkflow($object)) {
                return self::ACCESS_GRANTED;
            } else {
                return self::ACCESS_DENIED;
            }
        }
        return self::ACCESS_ABSTAIN;
    }
}