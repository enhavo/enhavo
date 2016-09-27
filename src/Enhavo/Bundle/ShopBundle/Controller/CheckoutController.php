<?php
/**
 * CheckoutController.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Model\CheckoutContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    /**
     * @return CartProviderInterface
     */
    protected function getCartProvider()
    {
        return $this->get('sylius.cart_provider');
    }

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

    /**
     * @return string
     */
    protected function getTemplate($name)
    {
        $templates = $this->container->getParameter('enhavo_shop.templates');
        return $templates[$name];
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
        $order = $this->getCurrentCart();

        $form = $this->get('form.factory')->create($context->getFormType());
        $form->setData($order);

        $form->handleRequest($context->getRequest());
        if($form->isSubmitted()) {
            if($form->isValid()) {
                if($context->getProcessor()) {
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
        $context->addData('baseTemplate', $this->getTemplate('base'));


        return $this->render($this->getTemplate($context->getTemplate()), $context->getData());
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
    public function checkoutAction()
    {
        $order = $this->getCurrentCart();

        if($order->isEmpty()) {
            throw $this->createNotFoundException();
        }

        return $this->redirectToRoute('enhavo_shop_theme_checkout_addressing');
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
        $context->setTemplate('checkout_addressing');
        $context->setProcessor($this->get('enhavo.order_processing.shipment_processor'));

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
        $context->setTemplate('checkout_payment');
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
        $context->setTemplate('checkout_confirm');
        $context->setProcessor($this->get('enhavo.order_processing.confirm_processor'));
        $context->setRouteParameters([
            'token' => $order->getToken()
        ]);

        return $this->processCheckoutContext($context);
    }

    public function finishAction(Request $request)
    {
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

        return $this->render($this->getTemplate('checkout_finish'), [
            'order' => $order,
            'baseTemplate' => $this->getTemplate('base')
        ]);
    }
}