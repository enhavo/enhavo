<?php
/**
 * CheckoutController.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\ShopBundle\Model\CheckoutContext;
use Sylius\Component\Order\SyliusCartEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends AppController
{
    /**
     * @return OrderInterface
     */
    protected function getCurrentCart()
    {
        $cart = $this->getCartProvider()->getCart();
        if(empty($cart->getId())) {
            throw $this->createNotFoundException();
        }
        return $cart;
    }

    /**
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->get('doctrine')->getManager();
    }

    protected function getErrors(FormInterface $form)
    {
        $messages = [];
        foreach($form->getErrors() as $message) {
            $messages[] = $message->getMessage();
        }
        return $messages;
    }

    protected function getUrl($routeName, $routeParameters)
    {
        return $this->get('router')->generate($routeName, $routeParameters);
    }

    protected function processCheckoutContext(CheckoutContext $context)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($context->getRequest());

        $order = $this->getCurrentCart();

        $form = $this->get('form.factory')->create($context->getFormType());
        $form->setData($order);

        $form->handleRequest($context->getRequest());
        if($form->isSubmitted()) {
            if($form->isValid()) {
                if($context->getProcessor()) {
                    $this->get('event_dispatcher')->dispatch(SyliusCartEvents::CART_CHANGE, new GenericEvent($order));
                    $context->getProcessor()->process($order);
                }
                $this->getManager()->flush();
                $url = $this->getUrl($context->getNextRoute(), $context->getRouteParameters());
                if($context->getRequest()->isXmlHttpRequest()) {
                    return new JsonResponse(['redirect_url' => $url], 200);
                }
                return $this->redirect($url);
            } else {
                if($context->getRequest()->isXmlHttpRequest()) {
                    return new JsonResponse($this->getErrors($form), 400);
                }
            }
        }

        $context->addData('order', $order);
        $context->addData('form', $form->createView());

        return $this->render($configuration->getTemplate($context->getTemplate()), $context->getData());
    }

    /**
     * @param Request $request
     * @return CheckoutContext
     */
    public function createCheckoutContext(Request $request)
    {
        $checkoutContext = new CheckoutContext();
        $checkoutContext->setRequest($request);
        return $checkoutContext;
    }

    /**
     * @return RedirectResponse
     */
    public function checkoutAction(Request $request)
    {
        try {
            $order = $this->getCurrentCart();
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('enhavo_shop_theme_checkout_empty');
        }

        if($order->isEmpty()) {
            return $this->redirectToRoute('enhavo_shop_theme_checkout_empty');
        }

        if($this->getUser()) {
            return $this->redirectToRoute('enhavo_shop_theme_checkout_addressing');
        }

        $this->setTargetPath($request, 'enhavo_shop_theme_checkout_addressing');
        return $this->redirectToRoute('enhavo_shop_theme_checkout_login');
    }

    public function emptyAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        return $this->render($configuration->getTemplate('EnhavoShopBundle:Theme:Checkout/empty.html.twig'));
    }

    public function loginAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        $order = $this->getCurrentCart();

        return $this->render($configuration->getTemplate('EnhavoShopBundle:Theme:Checkout/login.html.twig'), [
            'order' => $order
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addressingAction(Request $request)
    {
        $context = $this->createCheckoutContext($request);
        $context->setNextRoute('enhavo_shop_theme_checkout_payment');
        $context->setFormType('enhavo_shop_order_address');
        $context->setTemplate('EnhavoShopBundle:Theme:Checkout/addressing.html.twig');
        $context->setProcessor($this->get('enhavo.order_processing.addressing_processor'));

        return $this->processCheckoutContext($context);
    }

    public function paymentAction(Request $request)
    {
        $order = $this->getCurrentCart();
        if(empty($order->getShippingAddress())) {
            throw $this->createNotFoundException();
        }

        $context = $this->createCheckoutContext($request);
        $context->setNextRoute('enhavo_shop_theme_checkout_confirm');
        $context->setFormType('enhavo_shop_order_payment');
        $context->setTemplate('EnhavoShopBundle:Theme:Checkout/payment.html.twig');
        $context->setProcessor($this->get('enhavo.order_processing.payment_processor'));

        return $this->processCheckoutContext($context);
    }

    public function confirmAction(Request $request)
    {
        /** @var OrderInterface $order */
        $order = $this->getCurrentCart();
        if(empty($order->getPayment())) {
            throw $this->createNotFoundException();
        }

        $context = $this->createCheckoutContext($request);
        $context->setNextRoute('enhavo_shop_theme_checkout_finish');
        $context->setFormType('enhavo_shop_order_confirm');
        $context->setTemplate('EnhavoShopBundle:Theme:Checkout/confirm.html.twig');
        $context->setProcessor($this->get('enhavo.order_processing.confirm_processor'));
        $context->setRouteParameters([
            'token' => $order->getToken()
        ]);

        return $this->processCheckoutContext($context);
    }

    public function finishAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $token = $request->get('token');
        if($token === null) {
            throw $this->createNotFoundException();
        }

        $order = $this->get('sylius.repository.order')->findOneBy([
            'token' => $token
        ]);

        if($order === null) {
            throw $this->createNotFoundException();
        }

        return $this->render($configuration->getTemplate('EnhavoShopBundle:Theme:Checkout/finish.html.twig'), [
            'order' => $order,
        ]);
    }

    /**
     * @param Request $request
     */
    protected function setTargetPath(Request $request, $route, $parameters = [])
    {
        if ($request->hasSession() && $request->isMethodSafe(false) && !$request->isXmlHttpRequest()) {
            $request->getSession()->set('_security.user.target_path', $this->get('router')->generate($route, $parameters));
        }
    }
}
