<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderAddressingValidator extends ConstraintValidator
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, Constraint $constraint)
    {
        if(!$value instanceof OrderInterface) {
            $this->context->buildViolation('Value should implements OrderInterface')->addViolation();
        }

        if($value->getUser() === null) {
            $emailErrors = $this->validator->validate($value->getEmail(), [new NotNull(), new Email()]);
            foreach($emailErrors as $error) {
                $this->context->buildViolation($error->getMessage())->addViolation();
            }
        }

        $shippingAddressErrors = $this->validator->validate($value->getShippingAddress());
        if(count($shippingAddressErrors)) {
            /** @var ConstraintViolation $error */
            foreach($shippingAddressErrors as $error) {
                $this->context->buildViolation($error->getMessage())->addViolation();
            }
        }

        if($value->isDifferentBillingAddress()) {
            $billingAddressErrors = $this->validator->validate($value->getBillingAddress());
            if(count($billingAddressErrors)) {
                /** @var ConstraintViolation $error */
                foreach($billingAddressErrors as $error) {
                    $this->context->buildViolation($error->getMessage())->addViolation();
                }
            }
        }
    }
}