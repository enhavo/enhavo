<?php

namespace Enhavo\Bundle\PaymentBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Component\Core\Model\PaymentInterface;

final class ResolveNextRouteAction implements ActionInterface
{
    /**
     * @param ResolveNextRoute $request
     */
    public function execute($request): void
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        if (
            $payment->getState() === PaymentInterface::STATE_COMPLETED ||
            $payment->getState() === PaymentInterface::STATE_AUTHORIZED
        ) {
            $request->setRouteName(
                'sylius_shop_order_thank_you'
            );

            return;
        }

        $request->setRouteName('sylius_shop_order_show');
        $request->setRouteParameters(['tokenValue' => $payment->getOrder()->getTokenValue()]);
    }

    public function supports($request): bool
    {
        return
            $request instanceof ResolveNextRoute &&
            $request->getFirstModel() instanceof PaymentInterface
        ;
    }
}
