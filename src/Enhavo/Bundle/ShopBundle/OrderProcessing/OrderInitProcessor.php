<?php
/**
 * OrderInitProcessor.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Security\Generator\TokenGenerator;
use Sylius\Component\Core\OrderCheckoutStates;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OrderInitProcessor implements ProcessorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $orderRepository;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(RepositoryInterface $orderRepository, TokenGenerator $tokenGenerator, TokenStorage $tokenStorage)
    {
        $this->orderRepository = $orderRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_CART);
        $order->setUser($this->getUser());

        if($order->getNumber() === null) {
            $order->setNumber($this->generateNumber());
        }

        if($order->getToken() === null) {
            $order->setToken($this->tokenGenerator->generate(40));
        }
    }

    private function generateNumber()
    {
        /** @var OrderInterface[] $orders */
        $orders = $this->orderRepository->findBy([], [
            'number' => 'DESC'
        ], 1);

        if(count($orders)) {
            $number = $orders[0]->getNumber();
            if($number !== null) {
                $number++;
                return $number;
            }
        }
        return 10000;
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if($token) {
            return $token->getUser();
        }
        return null;
    }
}