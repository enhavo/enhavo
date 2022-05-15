<?php
/**
 * PaymentController.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Entity\PaymentMethod;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Payum\Core\Model\GatewayConfigInterface;
use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Payment\Model\Payment;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
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

        $payment = $order->getLastPayment(Payment::STATE_CART);

        $payumToken = $this->provideTokenBasedOnPayment($payment, 'enhavo_shop_theme_payment_after');

        return $this->redirect($payumToken->getTargetUrl());
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

    private function provideTokenBasedOnPayment(PaymentInterface $payment, $route, $parameters = []): TokenInterface
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $payment->getMethod();

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod;

        if (isset($gatewayConfig->getConfig()['use_authorize']) && true === (bool) $gatewayConfig->getConfig()['use_authorize']) {
            $token = $this->getTokenFactory()->createAuthorizeToken(
                $gatewayConfig->getGatewayName(),
                $payment,
                $route
                ?? null,
                $parameters
                ?? []
            );
        } else {
            $token = $this->getTokenFactory()->createCaptureToken(
                $gatewayConfig->getGatewayName(),
                $payment,
                $route
                ?? null,
                $parameters
                ?? []
            );
        }

        return $token;
    }
}
