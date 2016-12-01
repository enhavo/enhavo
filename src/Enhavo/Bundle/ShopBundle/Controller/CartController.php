<?php
/**
 * CartController.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Bundle\CartBundle\Controller\CartController as SyliusCartController;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Cart\SyliusCartEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Cart\Event\CartEvent;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sylius\Component\Resource\Event\FlashEvent;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class CartController extends SyliusCartController
{
    use CartSummaryTrait;

    public function summaryAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $cart = $this->getCurrentCart();
        $form = $this->resourceFormFactory->create($configuration, $cart);

        $view = View::create()
            ->setTemplate($configuration->getTemplate('summary.html'))
            ->setData([
                'cart' => $cart,
                'form' => $form->createView(),
            ])
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function saveQuantityAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $id = intval($request->get('id'));
        $quantity = intval($request->get('quantity'));

        if($quantity < 1) {
            throw $this->createNotFoundException();
        }

        /** @var OrderItem $orderItem */
        $orderItem = $this->get('sylius.repository.order_item')->find($id);

        if(empty($orderItem)) {
            throw $this->createNotFoundException();
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
}