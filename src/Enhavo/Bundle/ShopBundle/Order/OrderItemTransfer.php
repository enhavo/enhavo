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
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class OrderItemTransfer
{
    use ContainerAwareTrait;

    /**
     * @var OrderItemQuantityModifierInterface
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
     * @var Session
     */
    protected $session;

    /**
     * ItemResolver constructor.
     *
     * @param OrderItemQuantityModifierInterface $modifier
     */
    public function __construct(
        OrderItemQuantityModifierInterface $modifier,
        Factory $orderItemFactory,
        EventDispatcherInterface $eventDispatcher,
        Session $session)
    {
        $this->modifier = $modifier;
        $this->orderItemFactory = $orderItemFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }

    public function transfer(OrderInterface $from, OrderInterface $to)
    {
        foreach($from->getItems() as $item) {
            if($item instanceof OrderItemInterface) {
                $product = $item->getProduct();
                $quantity = $item->getQuantity();
                if($product) {
                    if ($this->isValid($product, $quantity)) {
                        $this->addItem($product, $quantity, $to);
                    }
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

    protected function isValid(ProductInterface $product, $quantity)
    {
        if ($product === null) {
            $message = $this->container->get('translator')->trans('product.message.error.not_found', [], 'EnhavoShopBundle');
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }
        return true;
    }
}
