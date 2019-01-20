<?php
/**
 * CartController.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

class CartController extends \Sylius\Bundle\OrderBundle\Controller\OrderController
{
    use CartSummaryTrait;

    public function saveQuantityAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $id = intval($request->get('id'));
        $quantity = intval($request->get('quantity'));

        /** @var OrderItem $orderItem */
        $orderItem = $this->get('sylius.repository.order_item')->find($id);

        if(empty($orderItem)) {
            throw $this->createNotFoundException();
        }

        if($quantity < 1) {
            $this->removeItem($orderItem);
            return $this->redirectToCartSummary($configuration);
        }

        $this->get('sylius.order_item_quantity_modifier')->modify($orderItem, $quantity);

        $cart = $this->getCurrentCart();
        $event = new CartEvent($cart);
        $eventDispatcher = $this->getEventDispatcher();
        $eventDispatcher->dispatch(SyliusCartEvents::CART_CHANGE, new GenericEvent($cart));
        $eventDispatcher->dispatch(SyliusCartEvents::CART_SAVE_INITIALIZE, $event);
        $eventDispatcher->dispatch(SyliusCartEvents::CART_SAVE_COMPLETED, new FlashEvent());

        return $this->redirectToCartSummary($configuration);
    }

    protected function removeItem($item)
    {
        $cart = $this->getCurrentCart();
        $eventDispatcher = $this->getEventDispatcher();

        if (!$item || false === $cart->hasItem($item)) {
            // Write flash message
            $eventDispatcher->dispatch(SyliusCartEvents::ITEM_REMOVE_ERROR, new FlashEvent());
        }

        $event = new CartItemEvent($cart, $item);

        // Update models
        $eventDispatcher->dispatch(SyliusCartEvents::ITEM_REMOVE_INITIALIZE, $event);
        $eventDispatcher->dispatch(SyliusCartEvents::CART_CHANGE, new GenericEvent($cart));
        $eventDispatcher->dispatch(SyliusCartEvents::CART_SAVE_INITIALIZE, $event);

        // Write flash message
        $eventDispatcher->dispatch(SyliusCartEvents::ITEM_REMOVE_COMPLETED, new FlashEvent());
    }
}
