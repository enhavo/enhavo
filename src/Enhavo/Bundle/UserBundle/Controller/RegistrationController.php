<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent;
use Enhavo\Bundle\UserBundle\Event\FormEvent;
use Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent;
use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class RegistrationController extends AbstractController
{
    /** @var EventDispatcherInterface  */
    private $eventDispatcher;

    /** @var UserManager  */
    private $userManager;

    /** @var TemplateManager */
    private $templateManager;

    /** @var FactoryInterface */
    private $userFactory;

    /**
     * RegistrationController constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserManager $userManager
     * @param TemplateManager $templateManager
     * @param FactoryInterface $userFactory
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, UserManager $userManager, TemplateManager $templateManager, FactoryInterface $userFactory)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->templateManager = $templateManager;
        $this->userFactory = $userFactory;
    }


    public function registerAction(Request $request)
    {
        $config = $request->attributes->get('_config');

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->userManager->createForm($config, 'register', $user);
        $form->handleRequest($request);

        $valid = true;
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $form->getData();

                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_SUCCESS, $event);

                $this->userManager->updatePassword($user);
                $this->userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'register'));
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                $this->userManager->sendRegistrationConfirmMail($user, $config);

                return $response;


            } else {
                $valid = false;
            }
        }

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'register')), [
            'form' => $form->createView(),
        ])->setStatusCode($valid ? 200 : 400);
    }

    public function checkAction(Request $request)
    {
        $config = $request->attributes->get('_config');
        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'check')));
    }

    public function confirmAction(Request $request, $token)
    {
        $config = $request->attributes->get('_config');
        $user = $this->userManager->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_CONFIRM, $event);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'confirm'));
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    public function finishAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $config = $request->attributes->get('_config');

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'finish')), [

        ]);
    }

    private function getTemplate($template)
    {
        return $this->templateManager->getTemplate($template);
    }
}
