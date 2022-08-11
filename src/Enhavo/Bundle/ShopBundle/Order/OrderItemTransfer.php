<?php
/**
 * UserCartTransfer.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Factory\OrderItemFactory;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Bundle\OrderBundle\Factory\AddToCartCommandFactory;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;

class OrderItemTransfer
{
    public function __construct(
        private EntityManagerInterface $orderManager,
        private OrderModifierInterface $orderModifier,
        private AddToCartCommandFactory $addToCartCommandFactory,
        private OrderItemQuantityModifierInterface $quantityModifier,
        private OrderItemFactory $orderItemFactory,
    )
    {}

    public function transfer(OrderInterface $from, OrderInterface $to)
    {
        foreach ($from->getItems() as $item) {
            if ($item instanceof OrderItemInterface) {
                $productVariant = $item->getProduct()->getProductVariant();
                $quantity = $item->getQuantity();
                if ($productVariant) {
                    if ($this->isValid($productVariant, $quantity)) {
                        $this->addItem($productVariant, $quantity, $to);
                    }
                }
            }
        }
    }

    private function addItem(ProductVariantInterface $productVariant, $quantity, OrderInterface $cart)
    {
        $orderItem = $this->orderItemFactory->createWithProductVariant($productVariant);
        $this->quantityModifier->modify($orderItem, $quantity);
        $addToCartCommand = $this->createAddToCartCommand($cart, $orderItem);

        $this->orderModifier->addToOrder($addToCartCommand->getCart(), $addToCartCommand->getCartItem());

        $this->orderManager->flush();
    }

    protected function createAddToCartCommand(\Sylius\Component\Order\Model\OrderInterface $cart, \Sylius\Component\Order\Model\OrderItemInterface $cartItem): AddToCartCommandInterface
    {
        return $this->addToCartCommandFactory->createWithCartAndCartItem($cart, $cartItem);
    }

    protected function isValid(ProductVariantInterface $product, $quantity)
    {
        //ToDo: Check Inventory
        return true;
    }
}
