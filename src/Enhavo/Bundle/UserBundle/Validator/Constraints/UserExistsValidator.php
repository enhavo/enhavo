<?php

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
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (is_string($value)) {
            $user = $this->userRepository->loadUserByIdentifier($value);
            if ($user === null) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
