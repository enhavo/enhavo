<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Factory\UserFactory;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResetPasswordController
 * @package Enhavo\Bundle\UserBundle\Controller
 */
class ResetPasswordController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var TemplateManager */
    private $templateManager;

    /** @var UserFactory */
    private $userFactory;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param TemplateManager $templateManager
     * @param UserFactory $userFactory
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository, TemplateManager $templateManager, UserFactory $userFactory)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->templateManager = $templateManager;
        $this->userFactory = $userFactory;
    }

    public function requestAction(Request $request)
    {
        $config = $request->attributes->get('_config');

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $form = $this->userManager->createForm($config, 'reset_password_request', $user);
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userRepository->loadUserByUsername($user->getUsername());

                $this->userManager->resetPassword($user, $config, 'reset_password_request');
                $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'reset_password_request'));
                // todo: json response on xhttp

                return new RedirectResponse($url);

            } else {
                $valid = false;
            }
        }

        // todo: json response on xhttp

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'reset_password_request')), [
            'form' => $form->createView(),
        ])->setStatusCode($valid ? 200 : 400);
    }

    public function checkAction(Request $request)
    {
        $config = $request->attributes->get('_config');
        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'reset_password_check')));
    }

    public function confirmAction(Request $request, $token)
    {
        $config = $request->attributes->get('_config');
        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $form = $this->userManager->createForm($config, 'reset_password_confirm', $user);
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->changePassword($user);
                $url = $this->generateUrl($this->userManager->getRedirectRoute($config, 'reset_password_confirm'));
                // todo: json response on xhttp

                return new RedirectResponse($url);

            } else {
                $valid = false;
            }
        }

        // todo: json response on xhttp

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'reset_password_confirm')), [
            'user' => $user,
            'form' => $form->createView(),
            'token' => $token,
        ])->setStatusCode($valid ? 200 : 400);

    }

    public function finishAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $config = $request->attributes->get('_config');

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'reset_password_finish')), [

        ]);
    }

    private function getTemplate($template)
    {
        return $this->templateManager->getTemplate($template);
    }
}
