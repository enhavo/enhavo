<?php

namespace Enhavo\Bundle\ShopBundle\Context;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Factory\OrderFactory;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionCartContext implements CartContextInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private string $sessionKeyName,
        private OrderRepositoryInterface $orderRepository,
        private OrderFactory $factory,
        private EntityManagerInterface $em,
        private TokenStorageInterface $tokenStorage,
    ) { }

    public function getCart(): OrderInterface
    {
        if (!$this->requestStack->getSession()->has($this->sessionKeyName) || $this->requestStack->getSession()->get($this->sessionKeyName) === null) {
            $cart = $this->createCart();
        } else {
            $cart = $this->orderRepository->findCartById($this->requestStack->getSession()->get($this->sessionKeyName));
        }

        if (null === $cart) {
            $cart = $this->createCart();
        }

        return $cart;
    }

    public function clear()
    {
        $this->requestStack->getSession()->set($this->sessionKeyName, null);
    }

    private function createCart(): \Enhavo\Bundle\ShopBundle\Model\OrderInterface
    {
        $cart = $this->factory->createNewCart();

        $user = $this->tokenStorage->getToken()?->getUser();
        if ($user instanceof UserInterface) {
            $cart->setUser($user);
        }

        $this->em->persist($cart);
        $this->em->flush();

        $this->requestStack->getSession()->set($this->sessionKeyName, $cart->getId());

        return $cart;
    }
}
