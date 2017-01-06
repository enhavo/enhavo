<?php
/**
 * SubscriberExistsValidator.php
 *
 * @since 05/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Subscriber\SubscriberManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscriberExistsValidator extends ConstraintValidator
{
    /**
     * @var SubscriberManager
     */
    private $manager;

    /**
     * SubscriberExistsValidator constructor.
     *
     * @param SubscriberManager $manager
     */
    public function __construct(SubscriberManager $manager)
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
        if($value instanceof SubscriberInterface) {
            if($this->manager->exists($value, $value->getType())) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $value->getEmail())
                    ->addViolation();
            }
        }
    }
}