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
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Cart\Event\CartItemEvent;
use Sylius\Component\Cart\SyliusCartEvents;
use Sylius\Component\Resource\Event\FlashEvent;
use Sylius\Component\Cart\Model\CartItemInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CartTransfer
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
                    if ($this->isValid($product->getId(), $quantity)) {
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

    public function isValid($productId, $quantity)
    {
        /** @var Product $product */
        $product = $this->container->get('sylius.repository.product')->find($productId);
        $currentQuantity = $this->getCurrentQuantity($product);
        $total = $this->getCurrentTotal();
        
        if($quantity < 1) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Die Menge muss 1 oder höher sein.', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if (!$productId) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Die Ware konnte nicht gefunden werden.', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if (empty($product)) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Die Ware konnte nicht gefunden werden.', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if (!$product->getPublic()) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Die Ware konnte nicht gefunden werden.', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if ($product->getSellingPrice() == 0 || $product->getSellingPrice() === null) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Die Ware hat keinen Preis und konnte deshalb nicht hinzugefügt werden.', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if($product->getNumberAvailable() == 0) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Das Produkt ist derzeit nicht auf Lager', $product->getTitle());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }

        if(($product->getNumberAvailable() - ($quantity + $currentQuantity)) < 0) {
            $message = sprintf('"%s" konnte(n) nicht hinzugefügt werden: Es sind nur noch %s Stück der Ware auf Lager. Bitte wählen Sie eine andere Menge.', $product->getTitle(), $product->getNumberAvailable());
            $this->session->getFlashBag()->add('error', $message);
            return false;
        }
        // Everything went fine, return true.
        return true;
    }

    protected function getCurrentQuantity(ProductInterface $product)
    {
        $cart = $this->container->get('sylius.cart_provider')->getCart();
        if(empty($cart)) {
            return 0;
        }
        /** @var OrderItem $item */
        foreach($cart->getItems() as $item) {
            if($item->getProduct()->getId() == $product->getId()) {
                return $item->getQuantity();
            }
        }
        return 0;
    }

    protected function getCurrentTotal()
    {
        $cart = $this->container->get('sylius.cart_provider')->getCart();
        return $cart->getTotal();
    }
}