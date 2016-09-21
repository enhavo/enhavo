<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends ResourceController
{
    public function activateAction(Request $request)
    {
        $code = $request->get('code');

        $subscriber = $this->repository->findOneBy([
            'token' => $code,
            'active' => false
        ]);

        if($subscriber) {
            $subscriber->setActive(true);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('EnhavoNewsletterBundle:Subscriber:activate.html.twig', [
            'subscriber' => $subscriber
        ]);
    }

    public function addAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);
        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                if(!$this->getSubscriberManager()->exists($newResource)) {
                    $newResource = $form->getData();
                    $this->getSubscriberManager()->addSubscriber($newResource);
                } else {
                    $message = $this->getSubscriberManager()->handleExists($newResource);
                    return new JsonResponse($message, 400);
                }
            } else {
                return new JsonResponse('valid error', 400);
            }
        }
        return new JsonResponse('success');
    }

    protected function getSubscriberManager()
    {
        return $this->get('enhavo_newsletter.subscriber.manager');
    }
}
