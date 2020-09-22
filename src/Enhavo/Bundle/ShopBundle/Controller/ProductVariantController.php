<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ShopBundle\Entity\Product;
//use Sylius\Bundle\ProductBundle\Form\Type\ProductGenerateVariantsType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\ShopBundle\Form\Type\ProductGenerateVariantsType;

class ProductVariantController extends ResourceController
{
    /**
     * @param Request $request
     * @throws \Exception
     */
    public function generateAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $productId = $request->get('productId');
        /** @var ProductInterface $product */
        $product = $em->getRepository(Product::class)->findOneBy([
            'id' => $productId
        ]);

        //        $form = $this->get('form.factory')->create(ProductGenerateVariantsType::class, $product);
        $form = $this->get('form.factory')->create(ProductGenerateVariantsType::class, $product);
        $form->setData($product);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($form->isValid()) {
                /** @var RequestConfiguration $configuration */
                $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
                /** @var ProductVariantInterface $productVariant */
                $productVariant = $this->singleResourceProvider->get($configuration, $this->repository);

                $view = $this->viewFactory->create('app', [
                    'request_configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $productVariant,
                    'template' => 'admin/resource/product-variant/generate.html.twig',
                ]);

                return $this->viewHandler->handle($configuration, $view);

//                if($context->getProcessor()) {
//                    $this->get('event_dispatcher')->dispatch(SyliusCartEvents::CART_CHANGE, new GenericEvent($order));
//                    $context->getProcessor()->process($order);
//                }
//                $this->getManager()->flush();
//                $url = $this->getUrl($context->getNextRoute(), $context->getRouteParameters());
//                if($context->getRequest()->isXmlHttpRequest()) {
//                    return new JsonResponse(['redirect_url' => $url], 200);
//                }
//                return $this->redirect($url);
            } else {

            }
        }
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->singleResourceProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create('update', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $productVariant,
            'form' => $form,
//            'template' => 'admin/resource/product-variant/generate.html.twig',
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }
}
