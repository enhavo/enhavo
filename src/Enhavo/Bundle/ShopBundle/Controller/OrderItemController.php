<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceControllerTrait;
use Sylius\Bundle\OrderBundle\Controller\OrderItemController as BaseOrderItemController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderItemController extends BaseOrderItemController
{
    use ResourceControllerTrait;

    public function addAction(Request $request): Response
    {
        $response = parent::addAction($request);
        $this->clearFlashMessages();
        return $response;
    }

    public function removeAction(Request $request): Response
    {
        $response = parent::removeAction($request);
        $this->clearFlashMessages();
        return $response;
    }

    private function clearFlashMessages()
    {
        $this->get('session')->getFlashBag()->clear();
    }
}
