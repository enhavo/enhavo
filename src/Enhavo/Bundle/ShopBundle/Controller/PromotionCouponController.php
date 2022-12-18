<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use App\Form\CreatePromotionType;
use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    private function getGenerator()
    {
        return $this->container->get('sylius.promotion_coupon_generator');
    }

    private function getTemplateManager()
    {
        return $this->container->get('enhavo_app.template.manager');
    }
}
