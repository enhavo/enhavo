<?php

namespace Enhavo\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Step\AbstractControllerStep;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Cart\Model\CartInterface;
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
     * @return CartInterface
     */
    protected function getCurrentCart()
    {
        return $this->getCartProvider()->getCart();
    }

    /**
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->get('doctrine')->getManager();
    }

}
