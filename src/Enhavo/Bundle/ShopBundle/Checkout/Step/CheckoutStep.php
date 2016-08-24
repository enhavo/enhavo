<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Step\AbstractControllerStep;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Doctrine\Common\Persistence\ObjectManager;

abstract class CheckoutStep extends AbstractControllerStep
{
    /**
     * @return CartProviderInterface
     */
    protected function getCartProvider()
    {
        return $this->get('sylius.cart_provider');
    }

    /**
     * @return OrderInterface
     */
    protected function getCurrentCart()
    {
        $cart = $this->getCartProvider()->getCart();
        if(empty($cart->getId())) {
            throw $this->createNotFoundException();
        }
        return $cart;
    }

    /**
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->get('doctrine')->getManager();
    }

}
