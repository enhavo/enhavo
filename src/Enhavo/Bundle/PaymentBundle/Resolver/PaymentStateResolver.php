<?php

namespace Enhavo\Bundle\PaymentBundle\Resolver;

use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Payum\Core\Request\GetHumanStatus;

class PaymentStateResolver
{
    public function __construct(
        private ResourceManager $resourceManager,
    )
    {}

    public function resolve(PaymentInterface $payment, GetHumanStatus $status)
    {
        $transition = $this->getTransition($status->getValue(), $payment->getState());

        if ($transition && $this->resourceManager->canApplyTransition($payment, $transition, 'enhavo_payment')) {
            $this->resourceManager->update($payment, $transition,'enhavo_payment');
        }
    }

    private function getTransition($status, $currentState): ?string
    {
        switch ($status) {
            case GetHumanStatus::STATUS_FAILED:
            case GetHumanStatus::STATUS_EXPIRED:
            case GetHumanStatus::STATUS_UNKNOWN:
            case GetHumanStatus::STATUS_SUSPENDED:
                return 'fail';
            case GetHumanStatus::STATUS_CAPTURED:
                return 'complete';
            case GetHumanStatus::STATUS_CANCELED:
                return 'cancel';
            case GetHumanStatus::STATUS_PAYEDOUT:
            case GetHumanStatus::STATUS_REFUNDED:
                return 'refund';
            case GetHumanStatus::STATUS_PENDING:
            case GetHumanStatus::STATUS_NEW:
                return 'process';
            case GetHumanStatus::STATUS_AUTHORIZED:
                return $currentState == PaymentInterface::STATE_AUTHORIZED ? null : 'authorize';
        }

        throw new \InvalidArgumentException(sprintf('Can\'t resolve state for value "%s"', $status));
    }
}
