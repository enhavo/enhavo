<?php
/**
 * OrderController.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends ResourceController
{
    public function listOrdersAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $template = $configuration->getTemplate('EnhavoShopBundle:Theme:Order/list.html.twig');
        return $this->render($template, [

        ]);
    }

    public function showOrderAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $template = $configuration->getTemplate('EnhavoShopBundle:Theme:Order/show.html.twig');
        return $this->render($template, [

        ]);
    }

    public function transferOrderAction()
    {

    }
}