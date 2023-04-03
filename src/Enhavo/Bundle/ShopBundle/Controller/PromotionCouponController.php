<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Sylius\Component\Order\CartActions;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PromotionCouponController extends ResourceController
{
    public function createFormAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if (null === $promotionId = $request->attributes->get('promotionId')) {
            throw new NotFoundHttpException('No promotion id given.');
        }

        if (null === $promotion = $this->container->get('sylius.repository.promotion')->find($promotionId)) {
            throw new NotFoundHttpException('Promotion not found.');
        }

        $form = $this->createForm($configuration->getFormType(), null, []);

        $form->handleRequest($request);

        $response = new Response();
        if ($request->isMethod(Request::METHOD_POST) && $form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getGenerator()->generate($promotion, $form->getData());
            } else {
                $response->setStatusCode(400);
            }
        }

        return $this->render($this->getTemplateManager()->getTemplate('admin/view/modal-form.html.twig'), [
            'form' => $form->createView()
        ], $response);
    }

    public function addAction(Request $request)
    {
        $cart = $this->getCurrentCart();
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, CartActions::ADD);

        $form = $this->createForm($configuration->getFormType(), $cart, $configuration->getFormOptions());
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $this->getOrderProcessor()->process($cart);
            $cartManager = $this->getCartManager();
            $cartManager->flush();

            return new JsonResponse([
                'cart' => $this->getNormalizer()->normalize($cart, null, [
                    'groups' => ['cart']
                ]),
                'form' => $this->getVueForm()->createData($form->createView()),
            ]);
        } else {
            return new JsonResponse([
                'cart' => $this->getNormalizer()->normalize($this->getCurrentCart(), null, [
                    'groups' => ['cart']
                ]),
                'form' => $this->getVueForm()->createData($form->createView()),
            ], 400);
        }
    }

    public function removeAction(Request $request)
    {
        $cart = $this->getCurrentCart();
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, CartActions::ADD);

        if ($request->isMethod('POST')) {
            $cart->setPromotionCoupon(null);
            $this->getOrderProcessor()->process($cart);
            $cartManager = $this->getCartManager();
            $cartManager->flush();
        }

        return new JsonResponse([
            'cart' => $this->getNormalizer()->normalize($this->getCurrentCart(), null, [
                'groups' => ['cart']
            ])
        ]);
    }

    private function getGenerator()
    {
        return $this->container->get('sylius.promotion_coupon_generator');
    }

    private function getTemplateManager()
    {
        return $this->container->get('enhavo_app.template.manager');
    }

    private function getCurrentCart(): OrderInterface
    {
        return $this->getContext()->getCart();
    }

    private function getContext(): CartContextInterface
    {
        return $this->get('sylius.context.cart');
    }

    private function getCartManager(): EntityManagerInterface
    {
        return $this->get('sylius.manager.order');
    }

    private function getOrderProcessor(): OrderProcessorInterface
    {
        return $this->get('sylius.order_processing.order_processor');
    }

    private function getNormalizer(): NormalizerInterface
    {
        return $this->get('serializer');
    }

    private function getVueForm(): VueForm
    {
        return $this->get(VueForm::class);
    }
}
