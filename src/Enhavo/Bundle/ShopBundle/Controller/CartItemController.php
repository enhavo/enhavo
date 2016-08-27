<?php
/**
 * CartItemController.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Sylius\Bundle\CartBundle\Controller\CartItemController as SyliusCartItemController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartItemController extends SyliusCartItemController
{
    public function addAction(Request $request)
    {
        $response = parent::addAction($request);
        if($request->isXmlHttpRequest()) {
            return new JsonResponse();
        }
        return $response;
    }
}