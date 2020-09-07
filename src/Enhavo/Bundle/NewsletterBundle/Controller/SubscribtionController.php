<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\AcceptStrategyType;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\DoubleOptInStrategyType;
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscribtionController extends AbstractController
{
    /** @var SubscribtionManager */
    private $subscribtionManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * SubscribtionController constructor.
     * @param SubscribtionManager $subscribtionManager
     * @param TranslatorInterface $translator
     */
    public function __construct(SubscribtionManager $subscribtionManager, TranslatorInterface $translator)
    {
        $this->subscribtionManager = $subscribtionManager;
        $this->translator = $translator;
    }


    public function activateAction(Request $request)
    {
        $type = $request->get('type');
        $subscribtion = $this->subscribtionManager->getSubscribtion($type);
        $strategy = $subscribtion->getStrategy();

        if (!($strategy instanceof DoubleOptInStrategyType)) {
            throw $this->createNotFoundException();
        }

        $token = $request->get('token');

        $subscriber = $strategy->findByToken($token, false);

        if (!$subscriber instanceof SubscriberInterface) {
            throw $this->createNotFoundException();
        }

        $strategy->activateSubscriber($subscriber, $type);

        return $this->render($strategy->getActivationTemplate($type), [
            'subscriber' => $subscriber
        ]);
    }

    public function acceptAction(Request $request)
    {
        $type = $request->get('type');
        $subscribtion = $this->subscribtionManager->getSubscribtion($type);
        $strategy = $subscribtion->getStrategy();

        if (!($strategy instanceof AcceptStrategyType)) {
            throw $this->createNotFoundException();
        }

        $token = $request->get('token');

        $subscriber = $strategy->findByToken($token, false);

        if (!$subscriber instanceof SubscriberInterface) {
            throw $this->createNotFoundException();
        }

        $strategy->activateSubscriber($subscriber, $type);

        return $this->render($strategy->getActivationTemplate($type), [
            'subscriber' => $subscriber
        ]);
    }

    public function addAction(Request $request)
    {
        $type = $request->get('type');

        $subscriber = new PendingSubscriber();
        $form = $this->createSubscriberForm($subscriber);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = $this->subscribtionManager->addSubscriber($subscriber, $type);
            return new JsonResponse([
                'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
                'subscriber' => $subscriber
            ]);
        } else {
            $errorResolver = $this->container->get('enhavo_contact.form_error_resolver');
            $errors = $errorResolver->getErrors($form);
            return new JsonResponse([
                'errors' => $errors,
                'subscriber' => $subscriber
            ], 400);
        }
    }

    private function setSubscriberType(SubscriberInterface $subscriber, Request $request)
    {
        $type = $request->get('type');
        $formResolver = $this->container->get('enhavo_newsletter.form_resolver');
        $resolveType = $formResolver->resolveType($type);
        if ($resolveType === null) {
            throw $this->createNotFoundException('type could not be resolved');
        }
        $subscriber->setType($type);
    }

    private function createSubscriberForm(SubscriberInterface $subscriber)
    {
        $subscribtion = $this->subscribtionManager->getSubscribtion($subscriber->getType());

        $formType = '';//$subscribtion->getFormType();
        $form = $this->createForm($formType, $subscriber);

        return $form;
    }

}
