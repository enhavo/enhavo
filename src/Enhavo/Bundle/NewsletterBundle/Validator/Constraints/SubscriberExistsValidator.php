<?php
/**
 * SubscriberExistsValidator.php
 *
 * @since 05/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscriberExistsValidator extends ConstraintValidator
{
    /**
     * @var SubscriptionManager
     */
    private $manager;

    /**
     * SubscriberExistsValidator constructor.
     *
     * @param SubscriptionManager $manager
     */
    public function __construct(SubscriptionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed $value
     *
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof SubscriberInterface) {
            $subscription = $this->manager->getSubscription($value->getSubscription());
            $strategy = $subscription->getStrategy();
            if ($strategy->exists($value)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%email%', $value->getEmail())
                    ->addViolation();
            }
        }
    }
}
