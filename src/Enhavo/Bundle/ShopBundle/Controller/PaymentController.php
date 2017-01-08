<?php
/**
 * PaymentController.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Sylius\Component\Payment\Model\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\PayumBundle\Request\GetStatus;

class PaymentController extends AppController
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
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        $token = $this->getHttpRequestVerifier()->verify($request);
        $this->getHttpRequestVerifier()->invalidate($token);

        $status = new GetStatus($token);
        $gatewayName = $token->getGatewayName();
        $payum =  $this->getPayum();
        $gateway = $payum->getGateway($gatewayName);
        $gateway->execute($status);
        $payment = $status->getFirstModel();
        $order = $this->getOrderByPayment($payment);
        $orderStateResolver = $this->get('enhavo.order.state_resolver');
        $orderStateResolver->resolvePaymentState($order);

        $this->getDoctrine()->getManager()->flush();
        
        return $this->render($configuration->getTemplate('EnhavoShopBundle:Theme/Payment:after.html.twig'), [
            'order' => $order,
            'status' => $status
        ]);
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

    protected function getOrderByPayment(Payment $payment)
    {
        $orderRepository = $this->get('sylius.repository.order');
        /** @var OrderInterface $order */
        return $orderRepository->findByPaymentId($payment->getId());
    }
}