<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ShopBundle\Entity\Product;
//use Sylius\Bundle\ProductBundle\Form\Type\ProductGenerateVariantsType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\ResourceActions;
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
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->singleResourceProvider->get($configuration, $this->repository);

        $em = $this->get('doctrine.orm.default_entity_manager');

        $productId = $request->get('productId');
        /** @var ProductInterface $product */
        $product = $em->getRepository(Product::class)->findOneBy([
            'id' => $productId
        ]);

        $form = $this->get('form.factory')->create(ProductGenerateVariantsType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if($form->isValid()) {
                $this->appEventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $product);
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $product);
                $this->manager->flush();
                $this->appEventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $product);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $product);
                $this->addFlash('success', $this->get('translator')->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $request->get('_route');
                return $this->redirectToRoute($route, [
                    'productId' => $product->getId(),
                    'tab' => $request->get('tab'),
                    'view_id' => $request->get('view_id')
                ]);
            } else {
                $this->addFlash('error', $this->get('translator')->trans('form.message.error', [], 'EnhavoAppBundle'));
                foreach($form->getErrors() as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        $view = $this->viewFactory->create('update', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $productVariant,
            'form' => $form,
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }
}
