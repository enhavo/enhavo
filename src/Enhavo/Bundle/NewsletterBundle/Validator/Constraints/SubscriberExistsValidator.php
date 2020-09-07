<?php
/**
 * SubscriberExistsValidator.php
 *
 * @since 05/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Validator\Constraints;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscriberExistsValidator extends ConstraintValidator
{
    /**
     * @var SubscribtionManager
     */
    private $manager;

    /**
     * SubscriberExistsValidator constructor.
     *
     * @param SubscribtionManager $manager
     */
    public function __construct(SubscribtionManager $manager)
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
                    ->setParameter('%email%', $value->getEmail())
                    ->addViolation();
            }
        }
    }
}
