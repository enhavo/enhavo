<?php
/**
 * UserCartMerger.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Cart;


use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Context\CartContext;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserCartMerger
 *
 * If user has a current cart with items, after login the current cart will be used and saved to user
 * If user has a current cart no items, after login a saved cart will be used now as current cart
 *
 * @package Enhavo\Bundle\ShopBundle\Cart
 */
class UserCartMerger
{
    /**
     * @var CartProviderInterface
     */
    private $userCartProvider;

    /**
     * @var CartProviderInterface
     */
    private $cartProvider;

    /**
     * @var CartContext
     */
    private $cartContext;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        CartProviderInterface $userCartProvider,
        CartProviderInterface $cartProvider,
        CartContext $cartContext,
        EntityManagerInterface $em)
    {
        $this->userCartProvider = $userCartProvider;
        $this->cartProvider = $cartProvider;
        $this->cartContext = $cartContext;
        $this->em = $em;
    }

    /**
     *
     * @param UserInterface $user
     */
    public function merge(UserInterface $user)
    {
        $cart = $this->cartProvider->getCart();

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