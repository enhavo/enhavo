<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends ResettingController
{
    /**
     * Change user password
     */
    public function resetPasswordAction(Request $request)
    {
        $error = '';
        if($request->getMethod() == 'POST') {
            $username = $request->get('username');
            $send = $this->sendResetEmail($username);
            if($send === true) {
                return $this->render('EnhavoUserBundle:Security:reset-password-success.html.twig');
            }
            $error = $send;
        }
        return $this->render('EnhavoUserBundle:Security:reset-password.html.twig', array(
            'error' => $error
        ));
    }

    protected function sendResetEmail($username)
    {
        $translator = $this->get('translator');
        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $translator->trans('reset.form.error.invalid-user', [], 'EnhavoUserBundle');
        }

//        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
//            $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
//            return $translator->trans('reset.form.error.non-expired', [], 'EnhavoUserBundle');
//        }

        $tokenGenerator = $this->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return true;
    }

    /**
     * Change user password
     */
    public function confirmResetPasswordAction(Request $request)
    {
        $token = $request->get('token');

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);


        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        $error = '';
        if($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);

                $user->setConfirmationToken(null);
                $user->setPasswordRequestedAt(null);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('enhavo_dashboard_index');
                    $response = new RedirectResponse($url);
                    $this->authenticateUser($user, $response);
                    return $response;
                }
            } else {
                foreach($form->getErrors() as $errorMessage) {
                    $error = $errorMessage;
                }
            }
        }

        return $this->render('EnhavoUserBundle:Security:reset-password-confirm.html.twig', array(
            'token' => $token,
            'error' => $error,
            'form' => $form->createView(),
        ));
    }

    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}