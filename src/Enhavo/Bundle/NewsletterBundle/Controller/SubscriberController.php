<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\AcceptStrategy;
use Enhavo\Bundle\NewsletterBundle\Strategy\DoubleOptInStrategy;
use ProjectBundle\Event\PreValidationEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriberController extends ResourceController
{
    public function activateAction(Request $request)
    {
        $strategy = $this->get('enhavo_newsletter.strategy_resolver')->resolve($request->get('type'));

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

        $strategy->activateSubscriber($subscriber, $request->get('type'));

        return $this->render($strategy->getActivationTemplate(), [
            'subscriber' => $subscriber
        ]);
    }

    public function acceptAction(Request $request)
    {
        $strategy = $this->get('enhavo_newsletter.strategy_resolver')->resolve($request->get('type'));

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

        $strategy->activateSubscriber($subscriber, $request->get('type'));

        return $this->render($strategy->getActivationTemplate(), [
            'subscriber' => $subscriber
        ]);
    }

    public function addAction(Request $request)
    {
        $translator = $this->get('translator');
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var SubscriberInterface $subscriber */
        $subscriber = $this->newResourceFactory->create($configuration, $this->factory);
        $configFromYml = $this->getParameter('enhavo_newsletter.forms');
        $form = $this->get('form.factory')->create($configFromYml[$request->get('type')]['type'], $subscriber);
        $form->handleRequest($request);

        if($form->isValid()) {
            if(!$this->getSubscriberManager()->exists($subscriber, $request->get('type'))) {
                $event = new SubscriberEvent($subscriber, $request->get('type'));
                $this->get('event_dispatcher')->dispatch(NewsletterEvents::EVENT_ADD_SUBSCRIBER, $event);
                $message = $this->getSubscriberManager()->addSubscriber($subscriber, $request->get('type'));
                return new JsonResponse([
                    'message' => $translator->trans($message, [], 'EnhavoNewsletterBundle'),
                    'subscriber' => $subscriber
                ]);
            } else {
                $message = $this->getSubscriberManager()->handleExists($subscriber);
                return new JsonResponse([
                    'message' => $translator->trans($message, [], 'EnhavoNewsletterBundle')
                ], 400);
            }
        } else {
            return new JsonResponse([
                'message' => $translator->trans('subscriber.form.error.valid', [], 'EnhavoNewsletterBundle')
            ], 400);
        }
    }

    protected function getSubscriberManager()
    {
        return $this->get('enhavo_newsletter.subscriber.manager');
    }
}
