<?php

namespace Enhavo\Bundle\UserBundle\Validator\Constraints;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserEmailValidator extends ConstraintValidator
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * UserEmailValidator constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            throw new \InvalidArgumentException('UserEmailValidator is used, but no user is logged in');
        }

        $user = $token->getUser();
        if ($user === null) {
            throw new \InvalidArgumentException('UserEmailValidator is used, but no user is logged in');
        }

        if (!$user instanceof UserInterface) {
            throw new \InvalidArgumentException(sprintf('UserEmailValidator is used, but the logged in user does not implement "%s"', UserInterface::class));
        }

        if (is_string($value)) {
            if ($user->getEmail() !== $value) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
