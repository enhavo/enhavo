<?php
/**
 * OrderController.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceControllerTrait;
use Enhavo\Bundle\ShopBundle\Exception\DocumentGeneratorException;
use Enhavo\Bundle\ShopBundle\Manager\DocumentManager;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sylius\Bundle\OrderBundle\Controller\OrderController as SyliusOrderController;

class OrderController extends SyliusOrderController
{
    use ResourceControllerTrait;

    public function summaryAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'shop_cart_summary',
            'request_configuration' => $configuration
        ]);

        return $view->getResponse($request);
    }

    public function showAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var OrderInterface $order */
        $order = $this->singleResourceProvider->get($configuration, $this->repository);
        if($order === null) {
            throw $this->createNotFoundException();
        }
        if($order->getState() === 'cart') {
            throw $this->createNotFoundException();
        }

        $template = $configuration->getTemplate('theme/shop/order/show.html.twig');
        return $this->render($template, [
            'order' => $order
        ]);
    }

    public function documentAction(Request $request): Response
    {
        $config = $this->createDocumentConfiguration($request);

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $order = $this->singleResourceProvider->get($configuration, $this->repository);

        if ($config->isMutable()) {
            $formDataArray = [];
            $form = $this->resourceFormFactory->create($configuration, $order);
            if (key_exists($form->getName(), $formDataArray)) {
                $form->submit($formDataArray[$form->getName()]);
                if (!$form->isValid()){
                    return new JsonResponse($this->getErrors($form), 400);
                }
            }
        }

        $documentManager = $this->getDocumentManager();
        $file = $documentManager->generateDocument($config->getName(), $order, $config->getOptions());

        $disposition = $request->get('disposition');
        if (!in_array($disposition, ['attachment', 'inline'])) {
            $disposition = 'disposition';
        }

        $response = new Response();
        $response->setContent($file->getContent()->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set(
            'Content-Disposition',
            sprintf('%s; filename="%s"', $disposition, $file->getFilename())
        );

        return $response;
    }

    private function createDocumentConfiguration(Request $request): DocumentConfiguration
    {
        $config = $request->attributes->get('_config');

        if (!isset($config['name'])) {
            throw new DocumentGeneratorException('No name was found in route config. Expected under _config.name');
        }

        $mutable = false;
        if (isset($config['mutable'])) {
            $mutable = $config['mutable'];
        }

        $options = [];
        if (isset($config['options'])) {
            $options = $config['options'];
        }

        return new DocumentConfiguration($config['name'], $options, $mutable);
    }

    public function checkoutAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'shop_checkout',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    private function getOrderProvider()
    {
        return $this->get('enhavo_shop.order.order_provider');
    }

    private function getCartTransfer()
    {
        return $this->get('enhavo_shop.cart.cart_transfer');
    }

    private function getCart()
    {
        return $this->get('sylius.cart_provider')->getCart();
    }

    private function getDocumentManager(): DocumentManager
    {
        return $this->get('enhavo_shop.document_manager');
    }
}
