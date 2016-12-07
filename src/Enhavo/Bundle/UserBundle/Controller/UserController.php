<?php
/**
 * UserController.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\UserBundle\Entity\User;
use Enhavo\Bundle\UserBundle\Viewer\ResetPasswordViewer;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class UserController extends ResourceController
{
    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }

    public function resetPasswordAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        $error = false;
        $reset = false;
        if($request->getMethod() == 'POST') {
            $userManager = $this->getUserManager();
            $user = $userManager->findUserByUsernameOrEmail($request->get('username'));
            if($user === null) {
                $error = true;
            } else {
                $reset = true;
                $template = null;
                $viewer = $this->viewerFactory->createType($configuration, 'reset_password');
                if($viewer instanceof ResetPasswordViewer) {
                    $template = $viewer->getMailTemplate();
                    $route = $viewer->getConfirmRoute();
                }
                $userManager->sendResetEmail($user, $template, $route);
            }
        }

        return $this->render($configuration->getTemplate('EnhavoUserBundle:Admin:User/reset-password.html.twig'), array(
            'error' => $error,
            'reset' => $reset,
        ));
    }

    public function resetPasswordConfirmAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $token = $request->get('token');
        $formFactory = $this->get('fos_user.resetting.form.factory');
        $userManager = $this->getUserManager();
        $user = $userManager->findUserByConfirmationToken($token);
        $form = $formFactory->createForm();
        $form->setData($user);
        $error = null;
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user->setConfirmationToken(null);
                $user->setPasswordRequestedAt(null);
                $userManager->updateUser($user);
                $url = $this->generateUrl($configuration->getRedirectRoute('enhavo_dashboard_index'));
                $response = new RedirectResponse($url);
                $userManager->authenticateUser($user, $response);
                return $response;
            } else {
                foreach($form->getErrors() as $errorMessage) {
                    $error = $errorMessage;
                }
            }
        }

        return $this->render($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-password.html.twig'), array(
            'token' => $token,
            'error' => $error,
            'form' => $form->createView(),
        ));
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $formFactory = $this->get('fos_user.change_password.form.factory');
        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);
        $valid = true;
        if($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $userManager->updateUser($user);
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-password.html.twig')))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $form = $this->resourceFormFactory->create($configuration, $user);

        $valid = true;
        $form->handleRequest($request);
        if (in_array($request->getMethod(), ['POST'])) {
            if($form->isValid()) {
                $this->manager->flush();
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Theme:User/profile.html.twig')))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function registerAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var User $user */
        $user = $this->newResourceFactory->create($configuration, $this->factory);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->resourceFormFactory->create($configuration, $user);

        $form->handleRequest($request);

        $valid = true;
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $form->getData();
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('enhavo_user_theme_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

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
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Theme/User:register.html.twig')))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function confirmedAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = View::create()
            ->setData([
                'user' => $this->getUser(),
            ])
            ->setStatusCode(200)
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Theme/User:registration-confirmed.html.twig')))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function changeEmailAction(Request $request)
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $form = $this->resourceFormFactory->create($configuration, $user);

        $form->handleRequest($request);
        $valid = true;
        if($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $userManager = $this->getUserManager();
                $userManager->updateUser($user);
            } else {
                $valid = false;
            }
        }

        $view = View::create($form)
            ->setData([
                'form' => $form->createView(),
            ])
            ->setStatusCode($valid ? 200 : 400)
            ->setTemplate($configuration->getTemplate($configuration->getTemplate('EnhavoUserBundle:Admin:User/change-email.html.twig')))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }
}