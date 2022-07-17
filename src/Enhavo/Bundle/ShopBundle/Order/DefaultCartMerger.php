<?php

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\ShopBundle\Context\SessionCartContext;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Context\CartContext;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;

/**
 * Class DefaultCartMerger
 *
 * If user has a current cart with items, after login the current cart will be used and saved to user
 * If user has a current cart no items, after login a saved cart will be used now as current cart
 */
class DefaultCartMerger implements CartMergerInterface
{
    public function __construct(
        private SessionCartContext $sessionCartContext,
        private CartContextInterface $userCartContext,
        private EntityManagerInterface $em
    )
    {}

    public function merge(UserInterface $user)
    {
        /** @var OrderInterface $sessionCart */
        $sessionCart = $this->sessionCartContext->getCart();

        $userCart = null;
        try {
            $userCart = $this->userCartContext->getCart();
        } catch (CartNotFoundException $e) {
            // do nothing
        }

        if ($sessionCart->isEmpty() && ($userCart === null || $userCart->isEmpty())) {
            $sessionCart->setUser($user);
            $this->sessionCartContext->clear();
            $this->em->flush();
        } else if (!$sessionCart->isEmpty()) {
            $sessionCart->setUser($user);
            $this->sessionCartContext->clear();
            $this->em->flush();
        }
    }
}
