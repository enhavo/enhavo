<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;

class PaymentStep extends CheckoutStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        return $this->render('EnhavoShopBundle:Checkout:payment.html.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        return $this->complete();
    }
}
