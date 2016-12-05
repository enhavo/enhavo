<?php
/**
 * PaymentController.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\PayumBundle\Request\GetStatus;

class PaymentController extends Controller
{
    public function purchaseAction(Request $request)
    {
        $token = $request->get('token');
        if(empty($token)) {
            throw $this->createNotFoundException();
        }

        $orderRepository = $this->get('sylius.repository.order');
        /** @var OrderInterface $order */
        $order = $orderRepository->findOneBy([
            'token' => $token
        ]);

        if(empty($order)) {
            throw $this->createNotFoundException();
        }

        $this->get('enhavo.order_processing.purchase_processor')->process($order);
        $this->getDoctrine()->getManager()->flush();

        $payment = $order->getPayment();

        $captureToken = $this->getTokenFactory()->createCaptureToken(
            $payment->getMethod()->getGateway(),
            $payment,
            'enhavo_shop_theme_payment_after'
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function afterAction(Request $request)
    {
        $token = $this->getHttpRequestVerifier()->verify($request);
        $this->getHttpRequestVerifier()->invalidate($token);

        $status = new GetStatus($token);
        $this->getPayum()->getGateway($token->getGatewayName())->execute($status);
        $payment = $status->getFirstModel();
        $order = $payment->getOrder();
#        $this->checkAccessToOrder($order);

#        $orderStateResolver = $this->get('sylius.order_processing.state_resolver');
#        $orderStateResolver->resolvePaymentState($order);
#        $orderStateResolver->resolveShippingState($order);

#        $this->getOrderManager()->flush();
        if ($status->isCanceled() || $status->isFailed()) {
            return new Response('test');
        }

        return new Response('after payment');
    }

    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->get('payum');
    }

    /**
     * @return GenericTokenFactoryInterface
     */
    protected function getTokenFactory()
    {
        return $this->get('payum')->getTokenFactory();
    }

    /**
     * @return HttpRequestVerifierInterface
     */
    protected function getHttpRequestVerifier()
    {
        return $this->get('payum')->getHttpRequestVerifier();
    }
}