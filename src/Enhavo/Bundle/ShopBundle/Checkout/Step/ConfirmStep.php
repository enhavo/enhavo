<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\Form\FormInterface;

class ConfirmStep extends CheckoutStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();
        $form = $this->createForm('enhavo_shop_order_confirm', $order);
        return $this->renderStep($order, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();
        $form = $this->createForm('enhavo_shop_order_confirm', $order);

        $request = $context->getRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getManager()->flush();
            return $this->complete();
        }

        return $this->renderStep($order, $form);
    }

    protected function renderStep( OrderInterface $order, FormInterface $form)
    {
        return $this->render('EnhavoShopBundle:Checkout:confirm.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }
}
