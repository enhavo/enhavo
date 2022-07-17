<?php
/**
 * UserCartProvider.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Context;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Model\CartInterface;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserCartContext implements CartContextInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RepositoryInterface $orderRepository,
    )
    {}

    public function getCart(): OrderInterface
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new CartNotFoundException();
        }

        $carts = $this->orderRepository->findBy([
            'user' => $user,
            'state' => 'cart'
        ], [
            'createdAt' => 'DESC'
        ], 1);

        if (count($carts)) {
            return $carts[0];
        }
        throw new CartNotFoundException();
    }

    private function getUser(): ?UserInterface
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        if ($user instanceof UserInterface) {
            return $user;
        }
        return null;
    }
}
