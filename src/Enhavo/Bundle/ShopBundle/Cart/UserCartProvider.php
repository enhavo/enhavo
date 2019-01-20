<?php
/**
 * UserCartProvider.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Cart;


use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Model\CartInterface;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserCartProvider implements CartProviderInterface
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RepositoryInterface
     */
    private $cartRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(TokenStorage $tokenStorage, RepositoryInterface $cartRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->tokenStorage = $tokenStorage;
        $this->cartRepository = $cartRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function hasCart()
    {
        return null !== $this->getCart();
    }

    public function getCart()
    {
        $user = $this->getUser();
        $carts = $this->cartRepository->findBy([
            'user' => $user,
            'expiresAt' => null,
            'state' => 'cart'
        ], [
            'createdAt' => 'DESC'
        ], 1);

        if(count($carts)) {
            return $carts[0];
        }
        return null;
    }

    public function setCart(CartInterface $cart)
    {
        $user = $this->getUser();

        if($cart !== $this->getCart()) {
            $this->abandonCart();
        }

        if($cart instanceof OrderInterface && $user instanceof UserInterface) {
            $cart->setUser($user);
            $cart->setExpiresAt(null);
        }
    }

    public function abandonCart()
    {
        $cart = $this->getCart();

        if($cart !== null) {
            $this->eventDispatcher->dispatch(
                SyliusCartEvents::CART_ABANDON,
                new GenericEvent($cart)
            );
        }
    }

    /**
     * @return UserInterface|null
     */
    private function getUser()
    {
        $user = null;
        $token = $this->tokenStorage->getToken();
        if($token) {
            $user = $token->getUser();
        }
        return $user;
    }
}
