<?php

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Context\CartContext;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Order\Context\CartContextInterface;

/**
 * Class UserCartMerger
 *
 * If user has a current cart with items, after login the current cart will be used and saved to user
 * If user has a current cart no items, after login a saved cart will be used now as current cart
 */
class DefaultCartMerger implements CartMergerInterface
{
    public function __construct(
        private CartContextInterface $sessionCartContext,
        private CartContextInterface $userCartContext,
        private EntityManagerInterface $em
    )
    {}

    public function merge(UserInterface $user)
    {
        $cart = $this->sessionCartContext->getCart();

        if(!$cart->isEmpty()) {
            $this->userCartProvider->setCart($cart);
        } else {
            $userCart = $this->userCartProvider->getCart();
            if($userCart !== null && !$userCart->isEmpty()) {
                $this->cartContext->setCurrentCartIdentifier($userCart);
            }
        }

        $this->em->flush();
    }
}
