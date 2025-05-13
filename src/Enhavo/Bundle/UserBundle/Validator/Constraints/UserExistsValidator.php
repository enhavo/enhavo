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

use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserExistsValidator extends ConstraintValidator
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * UsernameExistsValidator constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (is_string($value)) {
            $user = $this->userRepository->loadUserByIdentifier($value);
            if (null === $user) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
