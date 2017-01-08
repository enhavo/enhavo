<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderPaymentValidator extends ConstraintValidator
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
            return;
        }

        $payment = $value->getPayment();
        if(!$payment instanceof PaymentInterface) {
            $this->context->buildViolation('Order doesn\'t contain payment')->addViolation();
            return;
        }

        $method = $payment->getMethod();
        if(!$method instanceof PaymentMethodInterface) {
            $this->context->buildViolation('Paymebt doesn\'t contain method')->addViolation();
            return;
        }
    }
}