<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Component\Order\Model\OrderInterface;

class FinishStep extends CheckoutStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();
        return $this->renderStep($order);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        throw $this->createNotFoundException();
    }

    protected function renderStep(OrderInterface $order)
    {
        return $this->render('EnhavoShopBundle:Checkout:finish.html.twig', [
            'order' => $order,
        ]);
    }
}
