<?php

namespace Enhavo\Bundle\PaymentBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PaymentBundle\Entity\Payment;
use Enhavo\Bundle\PaymentBundle\Entity\PaymentMethod;
use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Enhavo\Bundle\PaymentBundle\Resolver\PaymentStateResolver;
use Payum\Core\Model\GatewayConfigInterface;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Sync;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends ResourceController
{
    public function authorizeAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $payment = $this->getPayment($request);

        if ($payment->getState() === PaymentInterface::STATE_AUTHORIZED) {
            return $this->redirectToRoute($configuration->getRedirectRoute(null), $configuration->getRedirectParameters() ?? []);
        }

        $token = $this->getTokenFactory()->createAuthorizeToken(
            $this->getGatewayConfig($payment)->getGatewayName(),
            $payment,
            $configuration->getRedirectRoute(null) ?? null,
            $configuration->getRedirectParameters() ?? []
        );

        return new RedirectResponse($token->getTargetUrl());
    }

    public function afterAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $payment = $this->getPayment($request);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);

        $gateway = $this->getPayum()->getGateway($token->getGatewayName());
        $gateway->execute($status = new GetHumanStatus($payment));

        $this->getStateResolver()->resolve($payment, $status);

        return $this->redirectToRoute($configuration->getRedirectRoute(null), $configuration->getRedirectParameters() ?? []);
    }

    public function captureAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $payment = $this->getPayment($request);

        if ($payment->getState() !== PaymentInterface::STATE_AUTHORIZED) {
            return $this->redirectToRoute($configuration->getRedirectRoute(null), $configuration->getRedirectParameters() ?? []);
        }

        $token = $this->getTokenFactory()->createCaptureToken(
            $this->getGatewayConfig($payment)->getGatewayName(),
            $payment,
            $configuration->getRedirectRoute(null) ?? null,
            $configuration->getRedirectParameters() ?? []
        );

        return new RedirectResponse($token->getTargetUrl());
    }

    public function refreshStateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var PaymentInterface $payment */
        $payment = $this->singleResourceProvider->get($configuration, $this->repository);

        $gatewayConfig = $this->getGatewayConfig($payment);
        $gateway = $this->getPayum()->getGateway($gatewayConfig->getGatewayName());
        $gateway->execute(new Sync($payment));
        $gateway->execute($status = new GetHumanStatus($payment));

        $this->getStateResolver()->resolve($payment, $status);

        return $this->redirectToRoute($configuration->getRedirectRoute('update'), [
            'id' => $payment->getId(),
            'view_id' => $request->get('view_id')
        ]);
    }

    public function finishAction(Request $request): Response
    {
        return new JsonResponse('finish');
    }

    private function getPayment(Request $request): PaymentInterface
    {
        $payment = $this->repository->findOneByToken($request->get('tokenValue'));

        if (null === $payment) {
            throw new NotFoundHttpException(sprintf('Payment with token "%s" does not exist.', $payment));
        }

        return $payment;
    }

    private function getGatewayConfig(Payment $payment): GatewayConfigInterface
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $payment->getMethod();

        return $paymentMethod->getGatewayConfig();
    }

    private function getTokenFactory(): GenericTokenFactoryInterface
    {
        return $this->getPayum()->getTokenFactory();
    }

    private function getHttpRequestVerifier(): HttpRequestVerifierInterface
    {
        return $this->getPayum()->getHttpRequestVerifier();
    }

    private function getPayum()
    {
        return $this->container->get('payum');
    }

    private function getStateResolver(): PaymentStateResolver
    {
        return $this->container->get('enhavo_payment.resolver.payment_state_resolver');
    }
}
