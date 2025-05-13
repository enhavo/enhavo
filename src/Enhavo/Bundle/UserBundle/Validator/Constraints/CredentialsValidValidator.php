<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CredentialsValidValidator extends ConstraintValidator
{
    public function __construct(
        private AuthenticationError $authenticationError,
    ) {
    }

    /**
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
