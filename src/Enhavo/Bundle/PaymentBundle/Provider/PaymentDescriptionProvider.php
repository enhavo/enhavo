<?php

namespace Enhavo\Bundle\PaymentBundle\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PaymentDescriptionProvider implements PaymentDescriptionProviderInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getPaymentDescription(PaymentInterface $payment): string
    {
        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        return $this->translator->trans(
            'sylius.payum_action.payment.description',
            [
                '%items%' => $order->getItems()->count(),
                '%total%' => round($payment->getAmount() / 100, 2),
            ]
        );
    }
}
