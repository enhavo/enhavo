<?php
/**
 * OrderProvider.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class OrderProvider
{
    /**
     * @var RepositoryInterface
     */
    private $orderRepository;

    public function __construct(RepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return OrderInterface
     */
    public function getLastOrder(UserInterface $user)
    {
        $orders = $this->orderRepository->findBy([
            'user' => $user,
            'state' => 'confirmed'
        ], [
            'updatedAt' => 'DESC'
        ], 1);

        if(count($orders)) {
            return $orders[0];
        }
        return null;
    }

    public function getOrders(UserInterface $user)
    {
        return $this->orderRepository->findBy([
            'user' => $user,
            'state' => 'confirmed'
        ], [
            'updatedAt' => 'DESC'
        ]);
    }
}