<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\Form\FormInterface;

class AddressingStep extends CheckoutStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();

        $form = $this->get('form.factory')->create('enhavo_shop_order_address');
        $form->setData($order);

        return $this->renderStep($order, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();

        $form = $this->get('form.factory')->create('enhavo_shop_order_address');
        $form->setData($order);

        $request = $context->getRequest();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getManager()->flush();
            return $this->complete();
        }

        return $this->renderStep($order, $form);
    }

    protected function getShipmentProcessor()
    {
        return $this->get('enhavo.order_processing.shipment_processor');
    }

    protected function renderStep(OrderInterface $order, FormInterface $form)
    {
        return $this->render('EnhavoShopBundle:Checkout:addressing.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'parentTemplate' => ''
        ]);
    }
}
