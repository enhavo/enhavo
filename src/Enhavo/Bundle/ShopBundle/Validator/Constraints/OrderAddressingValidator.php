<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderAddressingValidator extends ConstraintValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ){
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value->getUser() === null) {
            $errors = $this->validator->validate($value->getEmail(), [new NotNull(), new Email()]);
            foreach ($errors as $error) {
                $this->context->buildViolation($error->getMessage())->atPath('email')->addViolation();
            }
        }
    }
}
