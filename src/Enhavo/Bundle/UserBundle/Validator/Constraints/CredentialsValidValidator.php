<?php

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CredentialsValidValidator extends ConstraintValidator
{
    public function __construct(
        private AuthenticationError $authenticationError
    )
    {
    }

    /**
     * @param mixed $value
     * @param CredentialsValid $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $error = $this->authenticationError->getError();

        if ($error) {
            $this->context->buildViolation($error)->addViolation();
        }
    }
}
