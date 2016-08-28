<?php
/**
 * CartItemController.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Bundle\CartBundle\Controller\CartItemController as SyliusCartItemController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartItemController extends SyliusCartItemController
{
    public function addAction(Request $request)
    {
        $response = parent::addAction($request);
        if($request->isXmlHttpRequest()) {
            return $this->createOrderCompositionResponse();
        }
        return $response;
    }

    public function removeAction(Request $request)
    {
        $response = parent::removeAction($request);
        if($request->isXmlHttpRequest()) {
            return $this->createOrderCompositionResponse();
        }
        return $response;
    }

    protected function createOrderCompositionResponse()
    {
        /** @var OrderInterface $cart */
        $cart = $this->getCurrentCart();
        $compositionCalculator = $this->get('enhavo_shop.calculator.order_composition_calculator');
        $orderComposition = $compositionCalculator->calculateOrder($cart);

        return new JsonResponse([
            'order' => $orderComposition->toArray()
        ]);
    }
}