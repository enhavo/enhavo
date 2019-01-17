<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-17
 * Time: 12:28
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use FOS\UserBundle\FOSUserEvents;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends AbstractController
{
    use UserConfigurationTrait;

    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * RegistrationController constructor.
     * @param FactoryInterface $userFactory
     * @param UserManager $userManager
     * @param EventDispatcher $eventDispatcher
     * @param ViewHandler $viewHandler
     */
    public function __construct(FactoryInterface $userFactory, UserManager $userManager, EventDispatcher $eventDispatcher, ViewHandler $viewHandler)
    {
        $this->userFactory = $userFactory;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->viewHandler = $viewHandler;
    }

    public function registerAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm($configuration->getForm(RegistrationFormType::class), $user);

        $form->handleRequest($request);

        $valid = true;
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $form->getData();
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $this->userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('enhavo_user_theme_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                $this->userManager->sendRegisterConfirmEmail($user, $configuration->getMailTemplate(), $configuration->getConfirmRoute());

                return $response;
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate('EnhavoUserBundle:Theme/User:register.html.twig'))
        ;

        return $this->viewHandler->handle($view);
    }

    public function confirmedAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $configuration = $this->createConfiguration($request);

        $view = View::create()
            ->setData([
                'user' => $this->getUser(),
            ])
            ->setStatusCode(200)
            ->setTemplate($configuration->getTemplate('EnhavoUserBundle:Theme/User:registration-confirmed.html.twig'))
        ;

        return $this->viewHandler->handle($view);
    }


    public function confirmAction(Request $request, $token)
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        $configuration = $this->createConfiguration($request);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl($configuration->getConfirmedRoute('enhavo_user_theme_user_registration_confirmed'));
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }
}