<?php

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    /** @var FactoryInterface */
    private $userFactory;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ResetPasswordController constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param TemplateManager $templateManager
     * @param FactoryInterface $userFactory
     * @param TranslatorInterface $translator
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository, TemplateManager $templateManager, FactoryInterface $userFactory, TranslatorInterface $translator)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->templateManager = $templateManager;
        $this->userFactory = $userFactory;
        $this->translator = $translator;
    }

    public function requestAction(Request $request)
    {
        $config = $request->attributes->get('_config');
        $action = 'reset_password_request';

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $form = $this->userManager->createForm($config, $action, $user);
        $form->handleRequest($request);

        $valid = true;
        $message = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userRepository->loadUserByUsername($user->getUsername());
                if ($user === null) {
                    $message = $this->translator->trans('reset.form.error.invalid-user', [], 'EnhavoUserBundle');
                    $this->addFlash('error', $message);

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'error' => true,
                            'errors' => [],
                            'message' => $message,
                        ]);
                    }
                } else {
                    $message = $this->translator->trans('reset.message.success', [], 'EnhavoUserBundle');
                    $this->addFlash('success', $message);

                    $this->userManager->resetPassword($user, $config, $action);
                    $route = $this->userManager->getRedirectRoute($config, $action);
                    if ($route) {
                        $url = $this->generateUrl($route);

                        if ($request->isXmlHttpRequest()) {
                            return new JsonResponse([
                                'error' => false,
                                'errors' => [],
                                'message' => $message,
                                'redirect_url' => $url,
                            ]);
                        }

                        return new RedirectResponse($url);
                    }
                }


            } else {
                $valid = false;

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => true,
                        'errors' => [], // todo: add errors from enhavo form error resolver
                    ]);
                }
            }
        }

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, $action)), [
            'form' => $form->createView(),
            'stylesheets' => $this->userManager->getStylesheets($config, $action),
            'javascripts' => $this->userManager->getJavascripts($config, $action),
            'data' => [
                'messages' => $this->getFlashMessages(),
            ],
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
        $action = 'reset_password_confirm';
        $user = $this->userRepository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('A user with confirmation token "%s" does not exist', $token));
        }

        $form = $this->userManager->createForm($config, $action, $user);
        $form->handleRequest($request);

        $valid = true;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->changePassword($user);
                $url = $this->generateUrl($this->userManager->getRedirectRoute($config, $action));

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => false,
                        'errors' => [],
                        'redirect_url' => $url,
                    ]);
                }


                return new RedirectResponse($url);

            } else {
                $valid = false;
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'error' => true,
                        'errors' => [], // todo: add errors from enhavo form error resolver
                    ]);
                }
            }
        }

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, $action)), [
            'user' => $user,
            'form' => $form->createView(),
            'token' => $token,
            'stylesheets' => $this->userManager->getStylesheets($config, $action),
            'javascripts' => $this->userManager->getJavascripts($config, $action),
            'data' => [
                'messages' => $this->getFlashMessages(),
            ]
        ])->setStatusCode($valid ? 200 : 400);

    }

    public function finishAction(Request $request)
    {
        $config = $request->attributes->get('_config');

        return $this->render($this->getTemplate($this->userManager->getTemplate($config, 'reset_password_finish')), [

        ]);
    }

    private function getTemplate($template)
    {
        return $this->templateManager->getTemplate($template);
    }

    protected function getFlashMessages()
    {
        $flashBag = $this->container->get('session')->getFlashBag();
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach ($types as $type) {
            foreach ($flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => is_array($message) ? $message['message'] : $message,
                    'type' => $type
                ];
            }
        }
        return $messages;
    }
}
