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

    public function __construct(RepositoryInterface $orderRepository, TokenGenerator $tokenGenerator)
    {
        $this->orderRepository = $orderRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function process(OrderInterface $order)
    {
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
}