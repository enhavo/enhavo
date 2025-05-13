<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(SubscriptionManager $manager)
    {
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof SubscriberInterface) {
            $subscription = $this->manager->getSubscription($value->getSubscription());
            $strategy = $subscription->getStrategy();
            if (null !== $value->getEmail() && $strategy->exists($value)) {
                $message = $strategy->handleExists($value);
                $this->context->buildViolation($message ?? $constraint->message)
                    ->setParameter('%email%', $value->getEmail())
                    ->addViolation();
            }
        }
    }
}
