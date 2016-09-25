<?php
/**
 * UserCartTransfer.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Cart;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Cart\Event\CartItemEvent;
use Sylius\Component\Cart\SyliusCartEvents;
use Sylius\Component\Resource\Event\FlashEvent;

class CartTransfer
{
    /**
     * @var OrderItemQuantityModifier
     */
    protected $modifier;

    /**
     * @var Factory
     */
    protected $orderItemFactory;

    /**
     * @var Factory
     */
    protected $eventDispatcher;

    /**
     * ItemResolver constructor.
     *
     * @param OrderItemQuantityModifier $modifier
     */
    public function __construct(
        OrderItemQuantityModifier $modifier,
        Factory $orderItemFactory,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->modifier = $modifier;
        $this->orderItemFactory = $orderItemFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function transfer(OrderInterface $from, OrderInterface $to)
    {
        foreach($from->getItems() as $item) {
            if($item instanceof OrderItemInterface) {
                $product = $item->getProduct();
                $quantity = $item->getQuantity();
                if($product) {
                    $this->addItem($product, $quantity, $to);
                }
            }
        }
    }

    private function addItem(ProductInterface $product, $quantity, OrderInterface $cart)
    {
        /** @var OrderItemInterface $item */
        $item = $this->orderItemFactory->createNew();
        $item->setUnitPrice($product->getPrice());
        $item->setProduct($product);
        $this->modifier->modify($item, $quantity);

        $event = new CartItemEvent($cart, $item);

        $this->eventDispatcher->dispatch(SyliusCartEvents::ITEM_ADD_INITIALIZE, $event);
        $this->eventDispatcher->dispatch(SyliusCartEvents::CART_CHANGE, new GenericEvent($cart));
        $this->eventDispatcher->dispatch(SyliusCartEvents::CART_SAVE_INITIALIZE, $event);
        $this->eventDispatcher->dispatch(SyliusCartEvents::ITEM_ADD_COMPLETED, new FlashEvent());
    }
}