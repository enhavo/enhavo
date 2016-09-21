<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\AcceptStrategy;
use Enhavo\Bundle\NewsletterBundle\Strategy\DoubleOptInStrategy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends ResourceController
{
    public function activateAction(Request $request)
    {
        $subscriberManager = $this->getSubscriberManager();
        $strategy = $subscriberManager->getStrategy();

        if(!$strategy instanceof DoubleOptInStrategy) {
            throw $this->createNotFoundException();
        }

        $token = $request->get('token');

        $subscriber = $this->repository->findOneBy([
            'token' => $token,
            'active' => false
        ]);

        if(!$subscriber instanceof SubscriberInterface) {
            throw $this->createNotFoundException();
        }

        $strategy->activateSubscriber($subscriber);

        return $this->render($strategy->getActivationTemplate(), [
            'subscriber' => $subscriber
        ]);
    }

    public function acceptAction(Request $request)
    {
        $subscriberManager = $this->getSubscriberManager();
        $strategy = $subscriberManager->getStrategy();

        if(!$strategy instanceof AcceptStrategy) {
            throw $this->createNotFoundException();
        }

        $token = $request->get('token');

        $subscriber = $this->repository->findOneBy([
            'token' => $token,
            'active' => false
        ]);

        if(!$subscriber instanceof SubscriberInterface) {
            throw $this->createNotFoundException();
        }

        $strategy->activateSubscriber($subscriber);

        return $this->render($strategy->getActivationTemplate(), [
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
